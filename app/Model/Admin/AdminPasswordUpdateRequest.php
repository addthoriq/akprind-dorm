<?php

namespace Kang\AkprindDorm\Model\Admin;

class AdminPasswordUpdateRequest
{
  public ?string $kode_admin = null;
  public ?string $oldPassword = null;
  public ?string $newPassword = null;
}
