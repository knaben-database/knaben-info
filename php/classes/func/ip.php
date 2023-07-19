<?php

/** Class to determine the ip of the person
 * @property string|null $ip
 * @property string|null $http_cf_country
 * @property string|null $http_cf_connecting_ip
 * @property string|null $http_x_forwarded_for
 * @property string|null $http_x_real_ip
 * @property string|null $remote_addr
 */
class Ip implements \JsonSerializable
{
    public function __construct()
    {
        $http_cf_country = $_SERVER["HTTP_CF_IPCOUNTRY"] ?? null;
        $http_cf_connecting_ip = $_SERVER["HTTP_CF_CONNECTING_IP"] ?? null;
        $http_x_forwarded_for = $_SERVER["HTTP_X_FORWARDED_FOR"] ?? null;
        $http_x_real_ip = $_SERVER["HTTP_X_REAL_IP"] ?? null;
        $remote_addr = $_SERVER["REMOTE_ADDR"] ?? null;

        $this->_data["http_cf_ipcountry"] = $http_cf_country;
        $this->_data["http_cf_connecting_ip"] = $http_cf_connecting_ip;
        $this->_data["http_x_forwarded_for"] = $http_x_forwarded_for;
        $this->_data["http_x_real_ip"] = $http_x_real_ip;
        $this->_data["remote_addr"] = $remote_addr;

        foreach ([$http_cf_connecting_ip, $http_x_forwarded_for, $http_x_real_ip, $remote_addr] as $ip){
            if ($ip == "")
                continue;
            foreach (explode(",", $ip) as $value){
                $value = trim($value);
                if ($value == "127.0.0.1")
                    continue;

                $this->_data["ip"] = $value;
                return;
            }
        }
    }

    /** Function to get all the data in array like format
     * @return mixed
     */
    public function jsonSerialize() : mixed
    {
        return $this->_data;
    }

    public function __toString()
    {
        return $this->_data["ip"];
    }

    /**
     * @throws Exception
     */
    public function __get($name) {
        if (isset($this->_data[$name])) {
            return $this->_data[$name];
        } else {
            throw new Exception("Undefined property: $name");
        }
    }

    protected $_data = array(
        "ip" => "0.0.0.0",
        "http_cf_country" => null,
        "http_cf_connecting_ip" => null,
        "http_x_forwarded_for" => null,
        "http_x_real_ip" => null,
        "remote_addr" => null
    );
}