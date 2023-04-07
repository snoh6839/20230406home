class Deck {
    private $suits;
    private $faces;
    private $cards;

    public function __construct() {
        $this->suits = array('스페이드', '하트', '다이아', '클로버');
        $this->faces = array('2', '3', '4', '5', '6', '7', '8', '9', '10', 'J', 'Q', 'K', 'A');
        $this->cards = array();

        foreach ($this->suits as $suit) {
            foreach ($this->faces as $face) {
                $this->cards[] = new Card($suit, $face);
            }
        }
    }

    public function shuffle() {
        shuffle($this->cards);
    }

    public function drawCard() {
        return array_shift($this->cards);
    }
}
class Card {
    private $suit;
    private $face;

    public function __construct($suit, $face) {
        $this->suit = $suit;
        $this->face = $face;
    }

    public function getSuit() {
        return $this->suit;
    }

    public function getFace() {
        return $this->face;
    }

    public function getValue() {
        if ($this->face == 'A') {
            return 11;
        } elseif (in_array($this->face, array('K', 'Q', 'J', '10'))) {
            return 10;
        } else {
            return intval($this->face);
        }
    }
}
class Player {
    private $name;
    private $hand;

    public function __construct($name) {
        $this->name = $name;
        $this->hand = array();
    }

    public function getName() {
        return $this->name;
    }

    public function getHand() {
        return $this->hand;
    }

    public function addCardToHand($card) {
        $this->hand[] = $card;
    }

    public function clearHand() {
        $this->hand = array();
    }

    public function getHandValue() {
        $value = 0;
        $aceCount = 0;

        foreach ($this->hand as $card) {
            $value += $card->getValue();

            if ($card->getFace() == 'A') {
                $aceCount++;
            }
        }

        while ($value > 21 && $aceCount > 0) {
            $value -= 10;
            $aceCount--;
        }

        return $value;
    }
}
class Dealer extends Player {
    public function __construct() {
        parent::__construct("딜러");
    }

    public function showFirstCard() {
        $firstCard = $this->getHand()[0];
        echo $firstCard->getFace() . $firstCard->getSuit() . " ";
    }

    public function play(Deck $deck) {
        while ($this->getHandValue() < 17) {
            $this->addCardToHand($deck->drawCard());
        }
    }
}
class Blackjack {
    private $deck;
    private $player;
    private $dealer;

    public function __construct() {
        $this->deck = new Deck();
        $this->deck->shuffle();
        $this->player = new Player("플레이어
private $playerHand;
private $dealerHand;

public function __construct()
{
    $this->createDeck(true);
    $this->playerHand = array();
    $this->dealerHand = array();
    $this->playerHand[] = $this->drawCard();
    $this->playerHand[] = $this->drawCard();
    $this->dealerHand[] = $this->drawCard();
    $this->dealerHand[] = $this->drawCard();
}

private function drawCard()
{
    if (empty($this->deck)) {
        $this->createDeck(true);
    }
    return array_shift($this->deck);
}
private function calculateHandValue($hand, $isDealer = false)
{
    $value = 0;
    $aceCount = 0;
    foreach ($hand as $card) {
        if ($card['face'] == 'A') {
            $aceCount++;
        } elseif (in_array($card['face'], array('K', 'Q', 'J', '10'))) {
            $value += 10;
        } else {
            $value += intval($card['face']);
        }
    }
    for ($i = 0; $i < $aceCount; $i++) {
        if ($value + 11 <= 21) {
            $value += 11;
        } else {
            $value += 1;
        }
    }
    if ($isDealer && count($hand) == 2 && $value >= 17 && $value <= 21) {
        $value += 10;
    }
    return $value;
}
