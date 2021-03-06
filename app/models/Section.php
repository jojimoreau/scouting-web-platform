<?php
/**
 * Belgian Scouting Web Platform
 * Copyright (C) 2014  Julien Dupuis
 * 
 * This code is licensed under the GNU General Public License.
 * 
 * This is free software, and you are welcome to redistribute it
 * under under the terms of the GNU General Public License.
 * 
 * It is distributed without any warranty; without even the
 * implied warranty of merchantability or fitness for a particular
 * purpose. See the GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 **/

/**
 * This Eloquent class represents a section of the unit
 * 
 * Columns:
 *   - name:                Name of the section
 *   - slug:                A slug for the section used in the urls
 *   - position:            Order of the section in the section list
 *   - section_type:        Type of section ('B', 'L', 'E' or 'P')
 *   - section_type_number: Official id of the section
 *   - color:               Color of the section for the calendar
 *   - email:               Contact e-mail address of the section
 *   - de_la_section:       Used in different titles and texts on the website
 *   - la_section:          Used in different titles and texts on the website
 *   - subgroup_name:       Designation of the subgroups in this section (e.g. 'Sizaine', 'Patrouille')
 */
class Section extends Eloquent {
  
  protected $guarded = array('id', 'created_at', 'updated_at');
  
  // Static list of section categories
  private static $CATEGORIES = array(
      "baladins" => array(
          "name" => "Baladins",
          "scouts" => "baladins",
      ),
      "louveteaux" => array(
          "name" => "Louveteaux",
          "scouts" => "louveteaux",
      ),
      "eclaireurs" => array(
          "name" => "Éclaireurs",
          "scouts" => "éclaireurs",
      ),
      "pionniers" => array(
          "name" => "Pionniers",
          "scouts" => "pionniers",
      ),
      "nutons" => array(
          "name" => "Nutons",
          "scouts" => "nutons",
      ),
      "lutins" => array(
          "name" => "Lutins",
          "scouts" => "lutins",
      ),
      "guides" => array(
          "name" => "Guides",
          "scouts" => "guides",
      ),
      "castors" => array(
          "name" => "Castors",
          "scouts" => "castors",
      ),
      "routiers" => array(
          "name" => "Routiers",
          "scouts" => "routiers",
      ),
      "scouts" => array(
          "name" => "Scouts",
          "scouts" => "scouts",
      ),
  );
  
  /**
   * Returns the list of section for use in a html select
   * 
   * @param array $discard  List of sections to discard from the list
   * @return type
   */
  public static function getSectionsForSelect($discard = array()) {
    // Converts discarded sections to ids
    $discardIds = array();
    foreach ($discard as $section) {
      if (is_numeric($section)) {
        $discardIds[] = $section;
      } else {
        $discardIds[] = $section->id;
      }
    }
    // Get sections
    if (count($discardIds)) {
      $sections = self::whereNotIn('id', $discardIds)
              ->orderBy('position')->get();
    } else {
      $sections = self::orderBy('position')->get();
    }
    // Create and return section list
    $sectionArray = array();
    foreach ($sections as $section) {
      $sectionArray[$section->id] = $section->name;
    }
    return $sectionArray;
  }
  
  /**
   * Returns the generic name of the scouts in this section, depending
   * on the section type ('baladins', 'louveteaux', 'éclaireurs', etc.)
   */
  public function getScoutName() {
    if (array_key_exists($this->section_category, self::$CATEGORIES)) {
      return self::$CATEGORIES[$this->section_category]['scouts'];
    }
    return "scouts";
  }
  
  /**
   * Returns the list of section categories for use in a html select
   */
  public static function categoriesForSelect() {
    $categories = array();
    foreach (self::$CATEGORIES as $category => $categoryData) {
      $categories[$category] = $categoryData["name"];
    }
    return $categories;
  }
  
}
