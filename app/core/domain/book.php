<?php

namespace MavenBooks\Core\Domain;

class Book extends \Maven\Core\DomainObject {

	private $description;
	private $name;
	private $price;
	private $salePrice;
	private $reserved = false;
	private $status;
	private $special = false;
	private $dateImprinted = false;
	private $footnote;
	private $imprint;
	private $reservationPassword;
	private $featured = false;
	private $isbn;
	private $publicationDate;
	private $publicationPlace;
	private $publicationYear;
	private $bibliography;
	private $author;
	private $subtitle;
	private $inventoryId;
	private $stockEnabled = false;
	private $stockQuantity;
	private $url;

	public function __construct ( $id = false ) {

		parent::__construct( $id );

		$rules = array(
			'description' => \Maven\Core\SanitizationRule::TextWithHtml,
			'name' => \Maven\Core\SanitizationRule::Text,
			'price' => \Maven\Core\SanitizationRule::Float,
			'salePrice' => \Maven\Core\SanitizationRule::Float,
			'reserved' => \Maven\Core\SanitizationRule::Boolean,
			'status' => \Maven\Core\SanitizationRule::Text,
			'special' => \Maven\Core\SanitizationRule::Boolean,
			'dateImprinted' => \Maven\Core\SanitizationRule::Boolean,
			'footnote' => \Maven\Core\SanitizationRule::Text,
			'imprint' => \Maven\Core\SanitizationRule::Text,
			'reservationPassword' => \Maven\Core\SanitizationRule::Text,
			'featured' => \Maven\Core\SanitizationRule::Boolean,
			'isbn' => \Maven\Core\SanitizationRule::Text,
			'publicationDate' => \Maven\Core\SanitizationRule::Text,
			'publicationPlace' => \Maven\Core\SanitizationRule::Text,
			'publicationYear' => \Maven\Core\SanitizationRule::Integer,
			'bibliography' => \Maven\Core\SanitizationRule::Text,
			'author' => \Maven\Core\SanitizationRule::Text,
			'subtitle' => \Maven\Core\SanitizationRule::Text,
			'inventoryId' => \Maven\Core\SanitizationRule::Text,
			'stockEnabled' => \Maven\Core\SanitizationRule::Boolean,
			'stockQuantity' => \Maven\Core\SanitizationRule::Integer,
			'price' => \Maven\Core\SanitizationRule::Float,
			'url' => \Maven\Core\SanitizationRule::Text
		);

		$this->setSanitizationRules( $rules );
	}

	public function getDescription () {
		return $this->description;
	}

	public function setDescription ( $description ) {
		$this->description = $description;
	}

	public function getName () {
		return $this->name;
	}

	public function setName ( $name ) {
		$this->name = $name;
	}

	public function getPrice () {
		return $this->price;
	}

	public function setPrice ( $price ) {

		$this->price = $price;
	}

	public function getSalePrice () {
		return $this->salePrice;
	}

	public function setSalePrice ( $salePrice ) {

		$this->salePrice = $salePrice;
	}

	public function isReserved () {
		return $this->reserved;
	}

	public function setReserved ( $reserved ) {
		$this->reserved = $reserved;
	}

	public function getStatus () {
		return $this->status;
	}

	public function setStatus ( $status ) {

		$this->status = $status;
	}

	public function isSpecial () {
		return $this->special;
	}

	public function setSpecial ( $special ) {
		$this->special = $special;
	}

	public function isDateImprinted () {
		return $this->dateImprinted;
	}

	public function setDateImprinted ( $dateImprinted ) {
		$this->dateImprinted = $dateImprinted;
	}

	public function getFootnote () {
		return $this->footnote;
	}

	public function setFootnote ( $footnote ) {
		$this->footnote = $footnote;
	}

	public function getImprint () {
		return $this->imprint;
	}

	public function setImprint ( $imprint ) {

		$this->imprint = $imprint;
	}

	public function getReservationPassword () {
		return $this->reservationPassword;
	}

	public function setReservationPassword ( $reservationPassword ) {
		$this->reservationPassword = $reservationPassword;
	}

	public function isFeatured () {
		return $this->featured;
	}

	public function setFeatured ( $featured ) {
		$this->featured = $featured;
	}

	public function getIsbn () {
		return $this->isbn;
	}

	public function setIsbn ( $isbn ) {
		$this->isbn = $isbn;
	}

	public function getPublicationDate () {
		return $this->publicationDate;
	}

	public function setPublicationDate ( $publicationDate ) {
		$this->publicationDate = $publicationDate;
	}

	public function getPublicationPlace () {
		return $this->publicationPlace;
	}

	public function setPublicationPlace ( $publicationPlace ) {
		$this->publicationPlace = $publicationPlace;
	}

	public function getPublicationYear () {
		return $this->publicationYear;
	}

	public function setPublicationYear ( $publicationYear ) {
		$this->publicationYear = $publicationYear;
	}

	public function getBibliography () {
		return $this->bibliography;
	}

	public function setBibliography ( $bibliography ) {
		$this->bibliography = $bibliography;
	}

	public function getAuthor () {
		return $this->author;
	}

	public function setAuthor ( $author ) {
		$this->author = $author;
	}

	public function getSubtitle () {
		return $this->subtitle;
	}

	public function setSubtitle ( $subtitle ) {
		$this->subtitle = $subtitle;
	}

	public function getInventoryId () {
		return $this->inventoryId;
	}

	public function setInventoryId ( $inventoryId ) {
		$this->inventoryId = $inventoryId;
	}

	public function isStockEnabled () {
		return $this->stockEnabled;
	}

	public function setStockEnabled ( $stockEnabled ) {
		$this->stockEnabled = $stockEnabled;
	}

	public function getStockQuantity () {
		return $this->stockQuantity;
	}

	public function setStockQuantity ( $stockQuantity ) {
		$this->stockQuantity = $stockQuantity;
	}

	public function getUrl () {
		return $this->url;
	}

	public function setUrl ( $url ) {
		$this->url = $url;
	}

}
