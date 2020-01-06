<?php 

require '../../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__."/../../");
$dotenv->load(__DIR__ . '/../../');

class ZPDO extends PDO{
    var $save_last_id = "";
    
    public function __construct() {
        parent::__construct(
            getenv('ZPDO_SERVER'),
            getenv('ZPDO_USER'),
            getenv('ZPDO_PASS')
        );
		// $this->query("SET NAMES utf8");
		$this->query("SET NAMES utf8mb4;");
    }

    /**
     * $profile_array 
     * LINE Social API から取れたprofile情報の配列
     * 
     * closer
     */
    public function insertLineUserTable($profile_array){
        $stmt = $this->prepare("INSERT INTO line_user_dtb (name, thum, line_uid) VALUES (:name, :thum, :user_id)");
        $stmt->bindValue(":name", $profile_array["displayName"]);
        $stmt->bindValue(":thum", $profile_array["pictureUrl"]);
        $stmt->bindValue(":user_id", $profile_array["userId"]);
        $stmt->execute();
    }
}