<?php

namespace Kang\AkprindDorm\Service;

use Exception;
use Kang\AkprindDorm\Config\Database;
use Kang\AkprindDorm\Exception\ValidationException;
use Kang\AkprindDorm\Model\Admin\AdminLoginRequest;
use Kang\AkprindDorm\Model\Admin\AdminLoginResponse;
use Kang\AkprindDorm\Model\Admin\AdminPasswordUpdateRequest;
use Kang\AkprindDorm\Model\Admin\AdminPasswordUpdateResponse;
use Kang\AkprindDorm\Model\Admin\AdminProfileUpdateRequest;
use Kang\AkprindDorm\Model\Admin\AdminProfileUpdateResponse;
use Kang\AkprindDorm\Repository\AdminRepository;

class AdminService {
  
  private AdminRepository $adminRepository;

  public function __construct(AdminRepository $adminRepository) {
    $this->adminRepository = $adminRepository;
  }

  public function login(AdminLoginRequest $request): AdminLoginResponse{
    $this->validateAdminLoginRequest($request);

    $admin = $this->adminRepository->findByEmail($request->email_admin);
    if ($admin == null) {
      throw new ValidationException("Id or Password is wrong");
    }
    if (password_verify($request->password, $admin->password)) {
      $response = new AdminLoginResponse();
      $response->admin = $admin;
      return $response;
    } else {
      throw new ValidationException("Id or Password is wrong");
    }
  }

  private function validateAdminLoginRequest(AdminLoginRequest $request){
    if ($request->email_admin == null  || $request->password == null || trim($request->email_admin) == "" || trim($request->password) == "") {
      throw new ValidationException("Id and Password can not blank");
    }
  }

  public function updateProfile(AdminProfileUpdateRequest $request): AdminProfileUpdateResponse {
    $this->validateAdminProfileUpdateRequest($request);
    try {
      Database::beginTransaction();

      $admin = $this->adminRepository->findById($request->kode_admin);
      if ($admin == null) {
        throw new ValidationException("Admin is not found");
      }

      $admin->nama_admin = $request->nama_admin;
      $admin->email_admin = $request->email_admin;
      $this->adminRepository->update($admin);

      Database::commitTransaction();
      $response = new AdminProfileUpdateResponse();
      $response->admin = $admin;
      return $response;
    } catch (Exception $e) {
      Database::rollbackTransaction();
      throw $e;
    }
  }

  private function validateAdminProfileUpdateRequest(AdminProfileUpdateRequest $request) {
    if ($request->email_admin == null  || $request->nama_admin == null || trim($request->email_admin) == "" || trim($request->nama_admin) == "") {
      throw new ValidationException("Id and Name can not blank");
    }
  }

  public function updatePassword(AdminPasswordUpdateRequest $request): AdminPasswordUpdateResponse {
    $this->validateAdminPasswordUpdateRequest($request);
    try {
      Database::beginTransaction();

      $admin = $this->adminRepository->findById($request->kode_admin);
      if ($admin == null) {
        throw new ValidationException("Admin is not found");
      }
      if (!password_verify($request->oldPassword, $admin->password)) {
        throw new ValidationException("Old password is wrong");
      }
      $admin->password = password_hash($request->newPassword, PASSWORD_BCRYPT);
      $this->adminRepository->update($admin);

      Database::commitTransaction();

      $response = new AdminPasswordUpdateResponse();
      $response->admin = $admin;

      return $response;
    } catch (Exception $e) {
      Database::rollbackTransaction();
      throw $e;
    }
  }

  public function validateAdminPasswordUpdateRequest(AdminPasswordUpdateRequest $request) {
    if ($request->kode_admin == null || $request->oldPassword == null || $request->newPassword == null || trim($request->kode_admin) == "" || trim($request->oldPassword) == "" || trim($request->newPassword) == "") {
      throw new ValidationException("Id, Old Password, New Password can not blank");
    }
  }
}
