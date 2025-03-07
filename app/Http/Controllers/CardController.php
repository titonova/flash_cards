<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

use App\Http\Requests\CardCreateForm;
use App\Models\CardCategories;
use App\Models\Cards;
use App\Models\CardTags;
use App\Models\Feedback;
use App\Models\Tags;

class CardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
    * Create a new flash card
    *
    * @return \Illuminate\View\View
    */
    public function createCard()
    {
        $existingCats = ( new CardCategories )->getCategories();

        return view(
            'cards.create',
            [
                'existingCats' => $existingCats
            ]
        );
    }

    /**
    * Edit an existing flash card
    *
    * @param integer $cardId
    *
    * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View
    */
    public function editCard($cardId)
    {
        $existingCats = ( new CardCategories )->getCategories();

        $cardRow = Cards::where('id', $cardId)->first();

        if ($cardRow) {
            return view(
                'cards.create',
                [
                    'existingCats' => $existingCats,
                    'cardRow' => $cardRow,
                    'cardTags' => CardTags::join('tags', 'card_tags.tag_id', '=', 'tags.id')->where('card_id', $cardId)->get()
                ]
            );
        }

        return redirect()->route('dashboard')->with('status', 'Card not found.');
    }

    /**
    * Store a flash card to the database
    *
    * @param \App\Http\Requests\CardCreateForm $formObj
    *
    * @return \Illuminate\Http\RedirectResponse
    **/
    public function saveCard(CardCreateForm $formObj)
    {
        $formData = array(
            'id' => ($formObj->get('card_id') ?? null),
            'category' => $formObj->get('category'),
            'difficulty' => $formObj->get('difficulty'),
            'problem' => $formObj->get('problem'),
            'solution' => $formObj->get('solution'),
        );

        Cards::upsert( $formData, 'id' );

        $cardId = (is_numeric($formData['id'])) ? $formData['id'] : DB::getPdo()->lastInsertId();

        $cardTags = ( new Tags )->saveCardTags( $cardId, $formObj->get('tags') );

        if (is_numeric($cardId)) {
            return redirect()->back()->with('status', 'Flash card saved successfully.')->withInput(['cardTags'=> $cardTags]);
        } else {
            return redirect()->back()->with('error', 'Error saving the flash card.')->withInput();
        }
    }

    /**
     * Delete a card from the system
     *
     * @param integer $cardId
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Exception if not numeric $cardId
     */
    public function deleteCard($cardId)
    {
        if (is_numeric($cardId)) {
            $cardRow = Cards::where('id', $cardId)->first();

            if ($cardRow) {
                $cardRow->delete();

                return redirect()->route('dashboard')->with('status', 'Successfully deleted the flash card.');
            } else {
                return redirect()->route('dashboard')->with('error', 'Error deleting the card from the system.');
            }
        }

        throw new \Exception('Non numeric card id provided.');
    }

    /**
    * View stored card feedback
    *
    * @return \Illuminate\View\View
    **/
    public function viewFeedback()
    {
        return view(
            'cards.feedback',
            [
                'cardFeedback' => Feedback::orderBy('id', 'DESC')->paginate(Config::get('flash_cards.results_per_page'))
            ]
        );
    }

    /**
    * Delete a feedback comment from the system
    *
    * @param integer $commentId
    * @return \Illuminate\Http\RedirectResponse
    *
    * @throws \Exception if not numeric $cardId
    **/
    public function deleteFeedback($commentId)
    {
        if (is_numeric($commentId)) {
            $feedbackRow = Feedback::where('id', $commentId)->first();

            if ($feedbackRow) {
                $feedbackRow->delete();

                return redirect()->route('card.feedback')->with('status', 'Successfully deleted the feedback.');
            } else {
                return redirect()->route('card.feedback')->with('error', 'Error deleting the comment from the system.');
            }
        }

        throw new \Exception('Non numeric card id provided.');
    }

    /**
    * Return a JSON array of all stored card tags
    *
    * @return \Illuminate\Http\JsonResponse
    **/
    public function getTags()
    {
        return response()->json(Tags::all(['id', 'tag']), 200);
    }
}
