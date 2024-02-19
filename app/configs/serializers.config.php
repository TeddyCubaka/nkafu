<?php
class SerializerInterface
{
    private static $db;
    private static $db_table;
    private static $model;
    public $data;
    protected $error = null;
    public $is_valid;
    public function __construct($db, $db_table, $model)
    {
        self::$db = $db;
        self::$db_table = $db_table;
        self::$model = $model;
    }

    private function find_all_with_params($filters, $allowedColumns, $sql, $many, $table = null)
    {
        foreach ($filters as $column => $value) {
            if (!in_array($column, $allowedColumns)) {
                return [
                    'code' => 400,
                    'message' => "Le filtre sur la colonne '$column' n'est pas autorisé."
                ];
            }
        }

        if (!empty($filters)) {
            $sql .= " WHERE ";

            $conditions = [];
            foreach ($filters as $column => $value) {
                $conditions[] = ($table ? $table . "." : "") . "$column = :$column";
            }

            $sql .= implode(" AND ", $conditions);
        }
        $sql .= " ORDER BY " . ($table ? ($table . ".") : "") . "created_at DESC";

        $statement = self::$db->prepare($sql);

        foreach ($filters as $column => $value) {
            $statement->bindValue(":$column", $value);
        }

        $statement->execute();
        $result = $many ? $statement->fetchAll(PDO::FETCH_ASSOC) :  $statement->fetch(PDO::FETCH_ASSOC);

        return $result;
    }

    public function get_pdo_error_data(\PDOException $err)
    {
        $error = [
            'code' => $err->getCode(),
            'message' => $err->getMessage(),
        ];

        if ($_ENV['INFO_ENVIRONMENT'] == 'DEV') {
            $error['file'] = $err->getFile();
            $error['line'] = $err->getLine();
            $error['trace'] = $err->getTrace();
        }

        return $error;
    }

    /**
     * This method will general.
     * All you'll have to do is to extends this to your dao classe.
     */
    public function find(array $params, $many = true)
    {
        try {
            $allowedColumns = get_object_vars(self::$model);
            $allowedColumns = array_keys(self::$model->data());
            $sql = "SELECT  * FROM " . self::$db_table;

            $result = $this->find_all_with_params($params, $allowedColumns, $sql, $many);

            $list_length = count($result);

            return $result;
        } catch (PDOException $err) {
            $this->error = $this->get_pdo_error_data($err);
            return [
                'code' => 500,
                'message' => 'une erreur s\'est produite. ' . $err->getMessage(),
                'error' => $err->getTrace()
            ];
        } catch (Exception $err) {
            throw new \Exception('Une erreur s\'est produite lors de la lecture');
        }
    }

    public function has_error()
    {
        return $this->error == null;
    }
}
