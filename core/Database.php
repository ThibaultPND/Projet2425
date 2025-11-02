<?php
/**
 * Class Database
 * Gestion simplifiée et sécurisée de la base de données via PDO.
 */
class Database {
    private string $host     = DB_HOST;
    private string $user     = DB_USER;
    private string $pass     = DB_PASS;
    private string $dbname   = DB_NAME;

    private ?PDO $dbh        = null; // Instance PDO
    private ?PDOStatement $stmt = null;
    private string $error    = '';

    public function __construct() {
        // DSN (Data Source Name)
        $dsn = "mysql:host={$this->host};dbname={$this->dbname};charset=utf8mb4";
        $options = [
            PDO::ATTR_PERSISTENT => true,            // Connexion persistante (plus performante)
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION // Lève des exceptions sur erreur
        ];

        try {
            $this->dbh = new PDO($dsn, $this->user, $this->pass, $options);
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
            error_log("Erreur connexion DB : " . $this->error);
            die('Erreur base de données.');
        }
    }

    /** 
     * Prépare une requête SQL
     */
    public function query(string $sql): void {
        $this->stmt = $this->dbh->prepare($sql);
    }

    /**
     * Lie une valeur à un paramètre SQL
     */
    public function bind(string $param, mixed $value, int $type = null): void {
        if (is_null($type)) {
            $type = match (true) {
                is_int($value)  => PDO::PARAM_INT,
                is_bool($value) => PDO::PARAM_BOOL,
                is_null($value) => PDO::PARAM_NULL,
                default         => PDO::PARAM_STR,
            };
        }
        $this->stmt->bindValue($param, $value, $type);
    }

    /**
     * Exécute la requête préparée
     */
    public function execute(): bool {
        return $this->stmt->execute();
    }

    /**
     * Retourne tous les résultats sous forme de tableau d'objets
     */
    public function resultSet(): array {
        $this->execute();
        return $this->stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Retourne une seule ligne de résultat
     */
    public function single(): object|false {
        $this->execute();
        return $this->stmt->fetch(PDO::FETCH_OBJ);
    }

    /**
     * Retourne le nombre de lignes affectées
     */
    public function rowCount(): int {
        return $this->stmt->rowCount();
    }

    /**
     * Dernier ID inséré
     */
    public function lastInsertId(): string|false {
        return $this->dbh->lastInsertId();
    }

    /**
     * Démarre une transaction
     */
    public function beginTransaction(): bool {
        return $this->dbh->beginTransaction();
    }

    /**
     * Valide une transaction
     */
    public function commit(): bool {
        return $this->dbh->commit();
    }

    /**
     * Annule une transaction
     */
    public function rollBack(): bool {
        return $this->dbh->rollBack();
    }

    /**
     * Ferme la connexion PDO
     */
    public function close(): void {
        $this->stmt = null;
        $this->dbh = null;
    }
}

