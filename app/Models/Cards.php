<?php
/**
* Eloquent model for storage of flash card content
*/
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

class Cards extends Model
{
    protected $table = 'cards';

    protected $fillable = [
                            'category',
                            'problem',
                            'difficulty',
                            'solution'
                          ];


    /**
    * Get cards by category
    *
    * @param integer $catId
    *
    * @return \Illuminate\Support\Collection $existingCats
    **/
    public function getByCat($catId)
    {
        $pageResults = Config::get('flash_cards.results_per_page');

        $catCards = $this->where('category', $catId)->orderBy('id', 'DESC')->paginate($pageResults);

        return $catCards;
    }

    /**
     *
     * Get an escaped html solution
     *
     * @return void
     */
    public function getHtmlEscapedSolution()
    {
        // Replace all text within <pre><code></code></pre> with escaped HTML
        $escapedSolution = preg_replace_callback(
            '/<pre><code>(.*?)<\/code><\/pre>/s',
            function ($matches) {
                return '<pre><code>'.htmlspecialchars($matches[1]).'</code></pre>';
            },
            $this->solution
        );

        // Remove the new lines after <pre><code>
        $escapedSolution = str_replace("<pre><code>\n", "<pre><code>",$escapedSolution);


        return trim($escapedSolution);
    }

}
