<?php

namespace App\Http\Controllers;

class TokenResponse {
    protected $id;
    protected $status;
    protected $cardNumberLength;
    protected $dateCreated;
    protected $bin;
    protected $lastFourDigits;
    protected $securityCodeLength;
    protected $expirationMonth;
    protected $expirationYear;
    protected $dateDue;
    protected $name;

    // Métodos para establecer valores
    public function setId($id) { $this->id = $id; }
    public function setStatus($status) { $this->status = $status; }
    public function setCardNumberLength($length) { $this->cardNumberLength = $length; }
    public function setDateCreated($date) { $this->dateCreated = $date; }
    public function setBin($bin) { $this->bin = $bin; }
    public function setLastFourDigits($digits) { $this->lastFourDigits = $digits; }
    public function setSecurityCodeLength($length) { $this->securityCodeLength = $length; }
    public function setExpirationMonth($month) { $this->expirationMonth = $month; }
    public function setExpirationYear($year) { $this->expirationYear = $year; }
    public function setDateDue($date) { $this->dateDue = $date; }
    public function setName($name) { $this->name = $name; }

    // Métodos para obtener valores
    public function getId() { return $this->id; }
    public function getStatus() { return $this->status; }
    public function getCardNumberLength() { return $this->cardNumberLength; }
    public function getDateCreated() { return $this->dateCreated; }
    public function getBin() { return $this->bin; }
    public function getLastFourDigits() { return $this->lastFourDigits; }
    public function getSecurityCodeLength() { return $this->securityCodeLength; }
    public function getExpirationMonth() { return $this->expirationMonth; }
    public function getExpirationYear() { return $this->expirationYear; }
    public function getDateDue() { return $this->dateDue; }
    public function getName() { return $this->name; }
}