<?php

namespace Kang\AkprindDorm\Service;

use Kang\AkprindDorm\Domain\SessionAdmin;
use Kang\AkprindDorm\Domain\Admin;
use Kang\AkprindDorm\Repository\SessionAdminRepository;
use Kang\AkprindDorm\Repository\AdminRepository;

class SessionAdminService
{
  public static string $COOKIE_NAME = "PHP-MVC-SESSION";
  private SessionAdminRepository $sessionAdminRepository;
  private AdminRepository $userRepository;

  public function __construct(SessionAdminRepository $sessionAdminRepository, AdminRepository $userRepository) {
    $this->sessionAdminRepository = $sessionAdminRepository;
    $this->userRepository = $userRepository;
  }

  public function create(string $userId): SessionAdmin{
    $session = new SessionAdmin();
    $session->id_session = uniqid();
    $session->kode_admin= $userId;

    $this->sessionAdminRepository->save($session);

    setcookie(self::$COOKIE_NAME, $session->id_session, time() + (60 * 60 * 24 * 30), "/");

    return $session;
  }

  public function destroy() {
    $sessionId = $_COOKIE[self::$COOKIE_NAME] ?? '';
    $this->sessionAdminRepository->deleteById($sessionId);

    setcookie(self::$COOKIE_NAME, '', 1, "/");
  }

  public function current(): ?Admin {
    $sessionId = $_COOKIE[self::$COOKIE_NAME] ?? '';

    $session = $this->sessionAdminRepository->findById($sessionId);

    if ($session == null) {
      return null;
    }

    return $this->userRepository->findById($session->kode_admin);
  }
}
