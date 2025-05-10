<?php

class Database
{
    private $host;
    private $db_name;
    private $username;
    private $password;
    public $conn;

    public function __construct()
    {
        // Veritabanı bilgilerini doğrudan tanımla
        $this->host = '192.178.74.77';
        $this->db_name = 'portalv2';
        $this->username = 'celikerler-admin';
        $this->password = '43A308bt*';

        $this->connect();
    }

    private function connect()
    {
        $this->conn = null;

        try {
            $this->conn = new PDO(
                "mysql:host={$this->host};dbname={$this->db_name}",
                $this->username,
                $this->password,
                [PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"]
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $exception) {
            echo "Bağlantı hatası: " . $exception->getMessage();
        }
    }

    private function tabloKontrol($tablo)
    {
        // Burada tablo adının geçerliliğini kontrol eden mantığı ekleyin
        // Örneğin, izin verilen tablo adlarını kontrol edebilirsiniz
        $gecerliTablolar = [
            'users',
            'urunler',
            'satis_temsilcileri',
            'urunler_fiyat_listesi',
            'teklifler',
            'teklifler_grup',
            'teklifler_grup_malzemeler',
            'fiyatlar',
            'doviz',
            'durumlar',
            'birimler'

        ];
        return in_array($tablo, $gecerliTablolar);
    }

    public function islemYap($islem, $tablo, $veri = null, $kosul = null, $sirala = null)
    {
        // Tablo adını kontrol et
        if (!$this->tabloKontrol($tablo)) {
            throw new Exception("Yetkisiz tablo adı. Tablo:$tablo Lütfen tablonun geçerliliğini kontrol ediniz.");
        }

        switch ($islem) {
            case 'ekle':
                $query = "INSERT INTO $tablo (" . implode(", ", array_keys($veri)) . ") VALUES (" . implode(", ", array_map(function ($key) {
                    return ":$key";
                }, array_keys($veri))) . ")";
                $stmt = $this->conn->prepare($query);
                foreach ($veri as $sutun => $deger) {
                    $stmt->bindValue(":$sutun", $deger);
                }
                return $stmt->execute();

            case 'guncelle':
                $query = "UPDATE $tablo SET ";
                $set = [];
                foreach ($veri as $sutun => $deger) {
                    $set[] = "$sutun = :$sutun";
                }
                $query .= implode(", ", $set) . " WHERE $kosul";
                $stmt = $this->conn->prepare($query);
                foreach ($veri as $sutun => $deger) {
                    $stmt->bindValue(":$sutun", $deger);
                }
                return $stmt->execute();

            case 'sil':
                $query = "DELETE FROM $tablo WHERE $kosul";
                $stmt = $this->conn->prepare($query);
                return $stmt->execute();

            case 'getir':
                $query = "SELECT * FROM $tablo";
                if ($kosul) {
                    $query .= " WHERE $kosul";
                }
                if ($sirala) {
                    $query .= " ORDER BY $sirala";
                }
                $stmt = $this->conn->prepare($query);
                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC);

            default:
                throw new Exception("Geçersiz işlem türü.");
        }
    }
    
        // Sor metodunu hem sorgu stringini hem de parametre dizisini alacak şekilde değiştiriyoruz
        public function Sor($sql, $params = [])
{
    try {
        // SQL sorgusunun SELECT ile başladığından emin olalım
        if (!preg_match('/^SELECT/i', trim($sql))) {
            throw new Exception("Bu fonksiyon sadece SELECT sorguları için kullanılabilir.");
        }

        $stmt = $this->conn->prepare($sql);

        // --- Parametreleri Manuel Bağlama ---
        // Sorgudaki her ? için params dizisinden değeri alıp bağla
        $paramIndex = 1; // PDO'da bindValue/bindParam 1'den başlar
        foreach ($params as $param) {
            // Parametrenin tipini belirlemeye çalışalım
            $pdoType = PDO::PARAM_STR; // Varsayılan olarak string
            if (is_int($param)) {
                $pdoType = PDO::PARAM_INT; // Integer ise INT olarak bağla
            } elseif (is_bool($param)) {
                $pdoType = PDO::PARAM_BOOL; // Boolean ise BOOL olarak bağla
            } elseif (is_null($param)) {
                $pdoType = PDO::PARAM_NULL; // NULL ise NULL olarak bağla
            }

            // bindValue ile parametreyi bağla (index 1'den başlar)
            $stmt->bindValue($paramIndex, $param, $pdoType);
            $paramIndex++;
        }
        // --- Manuel Bağlama Sonu ---

        $stmt->execute(); // Parametresiz execute

        return $stmt->fetchAll(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
        // Hata ayıklama açık olmalı
        $hataAyiklama = true;
        if ($hataAyiklama) {
             echo "SQL: " . htmlspecialchars($sql) . "<br>";
             echo "Parametreler: <pre>" . htmlspecialchars(print_r($params, true)) . "</pre><br>";
             throw new Exception("Sorgu hatası: " . $e->getMessage() . " (SQLSTATE: " . $e->getCode() . ")");
        }
        return false;
    } catch (Exception $e) {
         echo "Genel Hata: " . $e->getMessage() . "<br>";
         return false;
    }
}

    public function sonEklenenId() {
        return $this->conn->lastInsertId();
    }

}