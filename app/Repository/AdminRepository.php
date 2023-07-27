<?php

namespace Kang\AkprindDorm\Repository;

use Kang\AkprindDorm\Domain\Admin;

class adminRepository {
  private \PDO $connection;

  public function __construct(\PDO $connection) {
    $this->connection = $connection;
  }

  public function save(Admin $admin): Admin{
    $statement = $this->connection->prepare("INSERT INTO admin(kode_admin, nama_admin, email_admin, password) VALUES (?, ?, ?, ?)");
    $statement->execute([
      $admin->kode_admin,
      $admin->nama_admin,
      $admin->email_admin,
      $admin->password
    ]);
    return $admin;
  }

  public function update(Admin $admin): Admin {
    $statement = $this->connection->prepare("UPDATE admin SET nama_admin = ?, email_admin = ?, password = ? WHERE kode_admin = ?");
    $statement->execute([
      $admin->nama_admin,
      $admin->email_admin,
      $admin->password,
      $admin->kode_admin
    ]);
    return $admin;
  }
  public function findById(string $kode_admin): ?Admin {
    $statement = $this->connection->prepare("SELECT kode_admin, nama_admin, email_admin, password FROM admin WHERE kode_admin = ?");
    $statement->execute([$kode_admin]);

    try {
      if ($row = $statement->fetch()) {
        $admin = new Admin();
        $admin->kode_admin = $row['kode_admin'];
        $admin->nama_admin = $row['nama_admin'];
        $admin->email_admin = $row['email_admin'];
        $admin->password = $row['password'];
        return $admin;
      } else {
        return null;
      }
    } finally {
      $statement->closeCursor();
    }
  }
public function findByEmail(string $email_admin): ?Admin {
    $statement = $this->connection->prepare("SELECT kode_admin, nama_admin, email_admin, password FROM admin WHERE email_admin = ?");
    $statement->execute([$email_admin]);

    try {
      if ($row = $statement->fetch()) {
        $admin = new Admin();
        $admin->kode_admin = $row['kode_admin'];
        $admin->nama_admin = $row['nama_admin'];
        $admin->email_admin = $row['email_admin'];
        $admin->password = $row['password'];
        return $admin;
      } else {
        return null;
      }
    } finally {
      $statement->closeCursor();
    }
  }

  public function deleteAll(): void {
    $this->connection->exec("DELETE FROM admin");
  }
}
