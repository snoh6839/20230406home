<?php
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

    public function __construct($playerName) {
        $this->deck = new Deck();
        $this->player = new Player($playerName);
        $this->dealer = new Dealer();
    }

    public function start() {
        $this->deck->shuffle();
        $this->player->clearHand();
        $this->dealer->clearHand();

        $this->player->addCardToHand($this->deck->drawCard());
        $this->dealer->addCardToHand($this->deck->drawCard());
        $this->player->addCardToHand($this->deck->drawCard());
        $this->dealer->addCardToHand($this->deck->drawCard());

        $this->dealer->showFirstCard();
        echo "\n";

        while ($this->player->getHandValue() < 21) {
            $choice = readline("Hit or stand? ");

            if (strtolower($choice) == "hit") {
                $this->player->addCardToHand($this->deck->drawCard());
                echo $this->player->getName() . "'s hand: ";
                foreach ($this->player->getHand() as $card) {
                    echo $card->getFace() . $card->getSuit() . " ";
                }
                echo "\n";
            } else {
                break;
            }
        }

        $this->dealer->play($this->deck);

        echo "Dealer's hand: ";
        foreach ($this->dealer->getHand() as $card) {
            echo $card->getFace() . $card->getSuit() . " ";
        }
        echo "\n";

        if ($this->player->getHandValue() > 21) {
            echo $this->player->getName() . " busts! Dealer wins.\n";
        } elseif ($this->dealer->getHandValue() > 21) {
            echo "Dealer busts! " . $this->player->getName() . " wins.\n";
        } elseif ($this->player->getHandValue() > $this->dealer->getHandValue()) {
            echo $this->player->getName() . " wins.\n";
        } elseif ($this->player->getHandValue() < $this->dealer->getHandValue()) {
            echo "Dealer wins.\n";
        } else {
            echo "Push.\n";
        }
    }
}
$blackjack = new Blackjack();

// 플레이어 카드 두 장 받기
$blackjack->player->addCardToHand($blackjack->deck->drawCard());
$blackjack->player->addCardToHand($blackjack->deck->drawCard());

// 딜러 카드 한 장 받기
$blackjack->dealer->addCardToHand($blackjack->deck->drawCard());

// 플레이어 카드 보여주기
echo "플레이어의 카드: ";
foreach ($blackjack->player->getHand() as $card) {
    echo $card->getFace() . $card->getSuit() . " ";
}
echo "\n";

// 딜러 카드 보여주기 (첫 번째 카드는 가려둠)
echo "딜러의 카드: ";
$blackjack->dealer->showFirstCard();
echo " ***\n";

// 플레이어가 히트 또는 스탠드 선택하기
while ($blackjack->player->getHandValue() < 21) {
    $input = readline("Hit or Stand? (h/s) ");
    if ($input == 'h') {
        $blackjack->player->addCardToHand($blackjack->deck->drawCard());
        echo "플레이어의 카드: ";
        foreach ($blackjack->player->getHand() as $card) {
            echo $card->getFace() . $card->getSuit() . " ";
        }
        echo "\n";
    } else {
        break;
    }
}

// 플레이어가 21을 초과했으면 게임 종료
if ($blackjack->player->getHandValue() > 21) {
    echo "플레이어 패!\n";
} else {
    // 딜러가 17 이상이 될 때까지 카드 받기
    $blackjack->dealer->play($blackjack->deck);

    // 딜러 카드 보여주기
    echo "딜러의 카드: ";
    foreach ($blackjack->dealer->getHand() as $card) {
        echo $card->getFace() . $card->getSuit() . " ";
    }
    echo "\n";

    // 게임 승패 결정
    $playerValue = $blackjack->player->getHandValue();
    $dealerValue = $blackjack->dealer->getHandValue();
    if ($dealerValue > 21 || $playerValue > $dealerValue) {
        echo "플레이어 승!\n";
    } elseif ($playerValue < $dealerValue) {
        echo "딜러 승!\n";
    } else {
        echo "무승부!\n";
    }
}

// 게임 종료 후 카드 초기화
$blackjack->player->clearHand();
$blackjack->dealer->clearHand();


    ?>
