<?php

namespace PrestaShop\PrestaShop\Core\Domain\Product\Command;

use PrestaShop\PrestaShop\Core\Domain\Manufacturer\Exception\ManufacturerConstraintException;
use PrestaShop\PrestaShop\Core\Domain\Manufacturer\ValueObject\ManufacturerId;
use PrestaShop\PrestaShop\Core\Domain\Product\DTO\FeatureCollection;
use PrestaShop\PrestaShop\Core\Domain\Product\Exception\ProductConstraintException;
use PrestaShop\PrestaShop\Core\Domain\Product\ValueObject\Category;
use PrestaShop\PrestaShop\Core\Domain\Product\ValueObject\CostPrice;
use PrestaShop\PrestaShop\Core\Domain\Product\ValueObject\Image;
use PrestaShop\PrestaShop\Core\Domain\Product\ValueObject\ProductId;
use PrestaShop\PrestaShop\Core\Domain\Product\ValueObject\ProductName;
use PrestaShop\PrestaShop\Core\Domain\Product\ValueObject\RetailPrice;
use PrestaShop\PrestaShop\Core\Domain\Product\ValueObject\UnitPrice;

/**
 * Holds the abstraction of common product data which does not depend from product type.
 */
abstract class AbstractProductCommand
{
    /**
     * @todo: I need defaultLanguage validation in handler
     * @var ProductName[]
     */
    private $localisedProductNames;
    
    /**
     * @var Image[]
     */
    private $images;

    /**
     * @var CostPrice
     */
    private $costPrice;

    /**
     * @var RetailPrice
     */
    private $retailPrice;

    /**
     * @var UnitPrice
     */
    private $unitPrice;

    /**
     * @todo: I need cleanHtml validation in handler
     * @var array|string[]
     */
    private $localisedSummary;

    /**
     * @todo: I need cleanHtml validation in handler
     *
     * @var array|string[]
     */
    private $localisedDescription;

    /**
     * @var FeatureCollection
     */
    private $features;

    /**
     * @var ManufacturerId
     */
    private $manufacturerId;

    /**
     * @var ProductId[]
     */
    private $relatedProductIds;

    /**
     * @var Category[]
     */
    private $categories;

    /**
     * @var array
     */
    private $metaTitle;

    /**
     * @var array
     */
    private $metaDescription;

    /**
     * @var array
     */
    private $metaTags;

    /**
     * @todo: check if I am required in default language or I am set by product name etc... If so validate
     * @todo: I need link rewrite validation
     *
     * @var array
     */
    private $friendlyUrl;

    /**
     * @var null
     */
    private $redirectionPage;

    /**
     * @var null
     */
    private $visibility;

    /**
     * @var null
     */
    private $condition;

    /**
     * @var null
     */
    private $references;

    /**
     * @var array
     */
    private $customizationFields;

    /**
     * @var array
     */
    private $attachments;

    /**
     * @var int[]
     */
    private $shopAssociation;

    /**
     * @param string[] $localisedProductNames
     *
     * @throws ProductConstraintException
     */
    public function __construct(array $localisedProductNames)
    {
        $this->setLocalisedProductNames($localisedProductNames);
    }

    /**
     * @return ProductName[]
     */
    public function getLocalisedProductNames(): array
    {
        return $this->localisedProductNames;
    }

    /**
     * @return Image[]
     */
    public function getImages(): ?array
    {
        return $this->images;
    }

    /**
     * @param array Image[]
     *
     * @return self
     */
    public function setImages(array $images): self
    {
        $this->images = $images;

        return $this;
    }

    /**
     * @return CostPrice
     */
    public function getCostPrice(): CostPrice
    {
        return $this->costPrice;
    }

    /**
     * @param CostPrice $costPrice
     *
     * @return self
     */
    public function setCostPrice(CostPrice $costPrice): self
    {
        $this->costPrice = $costPrice;

        return $this;
    }

    /**
     * @return RetailPrice
     */
    public function getRetailPrice(): RetailPrice
    {
        return $this->retailPrice;
    }

    /**
     * @param RetailPrice $retailPrice
     *
     * @return self
     */
    public function setRetailPrice(RetailPrice $retailPrice): self
    {
        $this->retailPrice = $retailPrice;

        return $this;
    }

    /**
     * @return UnitPrice
     */
    public function getUnitPrice(): UnitPrice
    {
        return $this->unitPrice;
    }

    /**
     * @param UnitPrice $unitPrice
     *
     * @return self
     */
    public function setUnitPrice(UnitPrice $unitPrice): self
    {
        $this->unitPrice = $unitPrice;

        return $this;
    }

    /**
     * @return array|string[]
     */
    public function getLocalisedSummary(): ?array
    {
        return $this->localisedSummary;
    }

    /**
     * @param array|string[] $localisedSummary
     *
     * @return self
     */
    public function setLocalisedSummary($localisedSummary): self
    {
        $this->localisedSummary = $localisedSummary;

        return $this;
    }

    /**
     * @return array|string[]
     */
    public function getLocalisedDescription(): array
    {
        return $this->localisedDescription;
    }

    /**
     * @param array|string[] $localisedDescription
     *
     * @return self
     */
    public function setLocalisedDescription($localisedDescription): self
    {
        $this->localisedDescription = $localisedDescription;

        return $this;
    }

    /**
     * @return FeatureCollection
     */
    public function getFeatures(): FeatureCollection
    {
        return $this->features;
    }

    /**
     * @param FeatureCollection $features
     *
     * @return self
     */
    public function setFeatures(FeatureCollection $features): void
    {
        $this->features = $features;

        return $this;
    }

    /**
     * @return ManufacturerId
     */
    public function getManufacturerId(): ManufacturerId
    {
        return $this->manufacturerId;
    }

    /**
     * @param int $manufacturerId
     *
     * @return self
     *
     * @throws ManufacturerConstraintException
     */
    public function setManufacturerId(int $manufacturerId): self
    {
        $this->manufacturerId = new ManufacturerId($manufacturerId);

        return $this;
    }

    /**
     * @return ProductId[]
     */
    public function getRelatedProductIds(): ?array
    {
        return $this->relatedProductIds;
    }

    /**
     * @param array|int[] $relatedProductIds
     *
     * @return self
     */
    public function setRelatedProductIds($relatedProductIds): self
    {
        $this->relatedProductIds = array_map(
            static function ($item) { return new ProductId($item); },
            $relatedProductIds
        );

        return $this;
    }

    /**
     * @return Category[]
     */
    public function getCategories(): ?array
    {
        return $this->categories;
    }

    /**
     * @param Category[] $categories
     *
     * @return self
     */
    public function setCategories(array $categories): self
    {
        $this->categories = $categories;

        return $this;
    }

    /**
     * @return array
     */
    public function getMetaTitle(): ?array
    {
        return $this->metaTitle;
    }

    /**
     * @param array $metaTitle
     *
     * @return self
     */
    public function setMetaTitle(array $metaTitle): self
    {
        $this->metaTitle = $metaTitle;

        return $this;
    }

    /**
     * @return array
     */
    public function getMetaDescription(): ?array
    {
        return $this->metaDescription;
    }

    /**
     * @param array $metaDescription
     *
     * @return self
     */
    public function setMetaDescription(array $metaDescription): self
    {
        $this->metaDescription = $metaDescription;

        return $this;
    }

    /**
     * @return array
     */
    public function getMetaTags(): ?array
    {
        return $this->metaTags;
    }

    /**
     * @param array $metaTags
     *
     * @return self
     */
    public function setMetaTags(array $metaTags): self
    {
        $this->metaTags = $metaTags;
        return $this;
    }

    /**
     * @return array
     */
    public function getFriendlyUrl(): ?array
    {
        return $this->friendlyUrl;
    }

    /**
     * @param array $friendlyUrl
     *
     * @return self
     */
    public function setFriendlyUrl(array $friendlyUrl): self
    {
        $this->friendlyUrl = $friendlyUrl;

        return $this;
    }

    /**
     * @return null
     */
    public function getRedirectionPage()
    {
        return $this->redirectionPage;
    }

    /**
     * @param null $redirectionPage
     *
     * @return self
     */
    public function setRedirectionPage($redirectionPage): self
    {
        $this->redirectionPage = $redirectionPage;

        return $this;
    }

    /**
     * @return null
     */
    public function getVisibility()
    {
        return $this->visibility;
    }

    /**
     * @param null $visibility
     *
     * @return self
     */
    public function setVisibility($visibility): self
    {
        $this->visibility = $visibility;

        return $this;
    }

    /**
     * @return null
     */
    public function getCondition()
    {
        return $this->condition;
    }

    /**
     * @param null $condition
     *
     * @return self
     */
    public function setCondition($condition): self
    {
        $this->condition = $condition;

        return $this;
    }

    /**
     * @return null
     */
    public function getReferences()
    {
        return $this->references;
    }

    /**
     * @param null $references
     *
     * @return self
     */
    public function setReferences($references): self
    {
        $this->references = $references;

        return $this;
    }

    /**
     * @return array
     */
    public function getCustomizationFields(): ?array
    {
        return $this->customizationFields;
    }

    /**
     * @param array $customizationFields
     *
     * @return self
     */
    public function setCustomizationFields(array $customizationFields): void
    {
        $this->customizationFields = $customizationFields;
        return $this;
    }

    /**
     * @return array
     */
    public function getAttachments(): ?array
    {
        return $this->attachments;
    }

    /**
     * @param array $attachments
     *
     * @return self
     */
    public function setAttachments(array $attachments): self
    {
        $this->attachments = $attachments;

        return $this;
    }

    /**
     * @param array $productNames
     * @return AbstractProductCommand
     *
     * @throws ProductConstraintException
     */
    private function setLocalisedProductNames(array $productNames): self
    {
        foreach ($productNames as $productName) {
            $this->localisedProductNames[] = new ProductName($productName);
        }
        
        return $this;
    }

    /**
     * @return int[]
     */
    public function getShopAssociation(): array
    {
        return $this->shopAssociation;
    }

    /**
     * @param int[] $shopAssociation
     *
     * @return self
     */
    public function setShopAssociation(array $shopAssociation): self
    {
        $this->shopAssociation = $shopAssociation;

        return $this;
    }
}
