<?php
/**
 * Category PDO mapped with categoeries table
 *
 * Created by PhpStorm.
 * User: tharwat
 * Date: 3/2/2019
 * Time: 01:26
 */
namespace youvids\domain\entities;

use phpchassis\data\entity\BaseEntity;
use phpchassis\data\mapper\DataMapperInterface;
use phpchassis\lib\collections\Collection;
use youvids\domain\mappers\CategoryMapper;

/**
 * Class Category
 * @package Youvids\data
 */
class Category extends BaseEntity {

  /**
   * @var string $tableName
   */
  protected static $tableName = "categories";

  /**
   * DB field
   *
   * @var string $name
   */
  private $name;

  /**
   * Getter/Setter for name
   *
   * @param null $name
   * @return string
   */
  public function name($name = null) {
    if($name === null) {
      return $this->name;
    }
    else {
      $this->name = $name;
    }
  }

  /**
   * Returns a list of list <options> populated with Categories
   * @param Collection $categories
   * @param int|null $selectedId
   * @return string
   */
  public static function categoryOptionsHtml(Collection $categories, int $selectedId = null): string {

    $categories = $categories->iterator();
    $html = '';

    foreach($categories as $category) {
        $selected = ($selectedId == $category->id()) ? "selected='selected'" : "";
        $html .= "<option value='{$category->id()}' {$selected}>{$category->name()}</option>";
    }
    return $html;
  }

  /**
   * Returns the Fully Qualified Class Name
   * @return string
   */
  public static function fqcn(): string {
    return self::class;
  }

  /**
   * Returns the associated data mapper for a particular Entity
   * @return DataMapperInterface
   */
  public function dataMapper(): DataMapperInterface {
    if (null == $this->dataMapper) {
      $this->dataMapper = new CategoryMapper();
    }
    return $this->dataMapper;
  }

  public function rules(): array { 
    return []; 
  }
}