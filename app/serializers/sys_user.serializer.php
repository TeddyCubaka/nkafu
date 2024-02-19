<?php
class Sys_user_serializer extends SerializerInterface
{

  public $old_id;

  private $db;

  function __construct($db)
  {
    $this->db = $db;
    $user = new Sys_user();
    parent::__construct($this->db, 'sys_user', $user);
  }

  public function Create($sys_user)
  {
    $qry = "INSERT INTO sys_user(login,pwd,statut,is_active,refresh,OTP,is_connect)  VALUES(:login,:pwd,:statut,:is_active,:refresh,:OTP,:is_connect)";

    $prep = $this->db->prepare($qry);

    $prep->bindValue(':login', ($sys_user->getLogin()));
    $prep->bindValue(':pwd', ($sys_user->getPwd()));
    $prep->bindValue(':statut', ($sys_user->getStatut()));
    $prep->bindValue(':is_active', (0));
    $prep->bindValue(':refresh', (0));
    $prep->bindValue(':OTP', ($sys_user->getOTP()));
    $prep->bindValue(':is_connect', (1));

    try {
      $prep->execute();

      // $sys_user->setId($this->db->lastInsertId());
      $sql = "SELECT sys_user.id as user_id, sys_user.* FROM sys_user WHERE login='" . $sys_user->getLogin() . "'";
      $th = $this->db->query($sql);
      $result = $th->fetchAll(PDO::FETCH_ASSOC);

      return array(
        'message' => 'L\'utilisateur a été créé.',
        'data' => $result[0],
        'code' => 201
      );
    } catch (PDOException $e) {
      header('HTTP/1.1 400 Bad Request');
      return array(
        'message' => "Une erreur s'est produite : " . $e->getMessage(),
        'data' => $sys_user->exec(),
        'code' => 400
      );
    }
  }
}
