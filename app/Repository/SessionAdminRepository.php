<?php

namespace Kang\AkprindDorm\Repository;

use Kang\AkprindDorm\Domain\SessionAdmin;

class SessionAdminRepository
{

  private \PDO $connection;

  public function __construct(\PDO $connection) {
    $this->connection = $connection;
  }

  public function save(SessionAdmin $session_admin): SessionAdmin{
    $statement = $this->connection->prepare("INSERT INTO session_admin(id_session, kode_admin) VALUES (?, ?)");
    $statement->execute([
      $session_admin->id_session,
      $session_admin->kode_admin
    ]);
    return $session_admin;
  }

  public function findById(string $id): ?SessionAdmin {
    $statement = $this->connection->prepare("SELECT id_session, kode_admin FROM session_admin WHERE id_session = ?");
    $statement->execute([$id]);
    try {
      if ($row = $statement->fetch()) {
        $session_admin = new sessionAdmin();
        $session_admin->id_session = $row['id_session'];
        $session_admin->kode_admin = $row['kode_admin'];
        return $session_admin;
      } else {
        return null;
      }
    }finally{
      $statement->closeCursor();
    }
  }

  public function deleteById(string $id): void{
    $statement = $this->connection->prepare("DELETE FROM session_admin WHERE id_session = ?");
    $statement->execute([$id]);
  }

  public function deleteAll(){
    $this->connection->exec("DELETE FROM session_admin");
  }
}
