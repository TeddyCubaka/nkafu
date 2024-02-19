 <?php
    class Sys_user extends ModelInterface
    {
        private $id;
        private $login;
        private $pwd;
        private $last_update;
        private $created_at;
        private $statut;
        private $refresh;
        private $is_connect;
        private $failed_login_count;
        private $two_factor_enabled;
        private $is_valid;
        private $OPT;
        private $is_active;

        public $pwd_unhashed;

        private $operator_prefixes = ['80', '81', '82 ', '83 ', '84 ', '85 ', '89 ', '90 ', '91 ', '92 ', '95 ', '97 ', '98 ', '99'];

        public function setId($id)
        {
            $this->id = $id;
        }
        public function setLogin($login)
        {
            if ($this->isValidPhoneNumber($login))
                $this->login = $login;
        }
        public function setPwd($pwd)
        {
            $this->pwd = $pwd;
            $this->pwd = $pwd;
        }
        public function hash_pwd($pwd)
        {
            $algorithm = PASSWORD_DEFAULT;
            $hash = password_hash($pwd, $algorithm);
            $this->pwd_unhashed = $pwd;
            $this->pwd = $hash;
        }
        public function setLast_update($last_update)
        {
            $this->last_update = $last_update;
        }
        public function setCreated_at($created_at)
        {
            $this->created_at = $created_at;
        }
        public function setStatut($statut)
        {
            $this->statut = $statut;
        }
        public function setRefresh($refresh)
        {
            $this->refresh = $refresh;
        }
        public function setIs_connect($is_connect)
        {
            $this->is_connect = $is_connect;
        }
        public function setFailedLoginCount($failed_login_count)
        {
            return $this->failed_login_count = $failed_login_count;
        }

        public function setTwoFactorEnabled($two_factor_enabled)
        {
            return $this->two_factor_enabled = $two_factor_enabled;
        }
        public function setIs_active($is_active)
        {
            $this->is_active = $is_active;
        }
        public function setOTP($OPT)
        {
            $this->OPT = $OPT;
        }

        public function getId()
        {
            return $this->id;
        }
        public function getLogin()
        {
            return $this->login;
        }
        public function getPwd()
        {
            return $this->pwd;
        }
        public function getLast_update()
        {
            return $this->last_update;
        }
        public function getCreated_at()
        {
            return $this->created_at;
        }
        public function getStatut()
        {
            return $this->statut;
        }
        public function getRefresh()
        {
            return $this->refresh;
        }
        public function getIs_connect()
        {
            return $this->is_connect;
        }
        public function getFailedLoginCount()
        {
            return $this->failed_login_count;
        }
        public function getTwoFactorEnabled()
        {
            return $this->two_factor_enabled;
        }
        public function isValidPhoneNumber($phoneNumber)
        {
            if (strlen($phoneNumber) != 9) {
                return false;
            }
            $operatorPrefix = substr($phoneNumber, 0, 2);
            if (!in_array($operatorPrefix, $this->operator_prefixes)) {
                return false;
            }
            return true;
        }
        public function getIs_active()
        {
            return $this->is_active;
        }
        public function getOTP()
        {
            return $this->OPT;
        }

        public function is_valid()
        {
            return $this->is_valid;
        }

        public function is_password_correct($pwd)
        {
            return password_verify($pwd, $this->pwd);
        }

        public function hydrate(array $data)
        {
            foreach ($data as $key => $value) {
                $method = 'set' . ucfirst($key);
                if (is_callable([$this, $method])) {
                    $this->$method($value);
                }
            }
        }

        public function exec()
        {
            $data = [];

            $reflectionClass = new ReflectionClass($this);
            $methods = $reflectionClass->getMethods(ReflectionMethod::IS_PUBLIC);

            foreach ($methods as $method) {
                $methodName = $method->getName();

                if (strpos($methodName, 'get') !== 0) {
                    continue;
                }

                $propertyValue = $this->$methodName();

                if ($propertyValue !== null) {
                    $propertyName = lcfirst(substr($methodName, 3));
                    if ($propertyName[0] == 'i') $propertyName = ucfirst($propertyName);

                    if (is_object($propertyValue)) {
                        $data[$propertyName] = $propertyValue->getID();
                    } else {
                        $data[$propertyName] = $propertyValue;
                    }
                }
            }

            return $data;
        }
    }
    ?>
