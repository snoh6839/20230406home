<?php
class Deck {
private $deck;

public function __construct() {
$this->deck = array();
$suits = array('스페이드', '하트', '다이아', '클로버');
$faces = array('2', '3', '4', '5', '6', '7', '8', '9', '10', 'J', 'Q', 'K', 'A');
foreach ($suits as $suit) {
foreach ($faces as $face) {
$this->deck[] = array('face' => $face, 'suit' => $suit);
}
}
shuffle($this->deck); // 덱을 무작위로 섞음
}

public function drawCard() {
$copy = $this->deck;
$card = array_shift($copy);
return $card;
}

public function calculateHandValue($hand) {
$value = 0;
$numAces = 0;
foreach ($hand as $card) {
if ($card['face'] == 'A') {
$numAces++;
} else if (in_array($card['face'], array('K', 'Q', 'J', '10'))) {
$value += 10;
} else {
$value += intval($card['face']);
}
}
// 에이스 처리
for ($i = 0; $i < $numAces; $i++) { if ($value + 11 <=21) { $value +=11; } else { $value +=1; } } return $value; } } class DrawDeck { private $deck; public function __construct() { $this->deck = new Deck();
    }

    public function userDrawCard(&$userHand) {
    $userHand[] = $this->deck->drawCard();
    echo "당신의 카드: ";
    foreach ($userHand as $card) {
    echo $card['face'] . $card['suit'] . " ";
    }
    echo "\n";
    }

    public function dealerDrawCard(&$dealerHand) {
    $dealerHand[] = $this->deck->drawCard();
    foreach ($dealerHand as $card) {
    return $card['face'] . $card['suit'] . " ";
    }
    }
    }

    class Player {
    protected $hand;
    protected $value;

    public function __construct() {
    $this->hand = array();
    $this->value = 0;
    }

    public function drawCard($deck) {
    $this->hand[] = $deck->drawCard();
    $this->value = $deck->calculateHandValue($this->hand);
    }

    public function getHand() {
    return $this->hand;
    }

    public function getValue() {
    return $this->value;
    }
    }

class User extends Player {
    public function __construct() {
        parent::__construct();
    }

    public function showHand() {
        echo "당신의 카드: ";
        foreach ($this->hand as $card) {
        echo $card['face'] . $card['suit'] . " ";
        }
        echo "\n";
    }
}

class Dealer extends Player
{
    public function __construct()
    {
        parent::__construct();
    }

    public function showHand()
    {
        echo "당신의 카드: ";
        foreach ($this->hand as $card) {
            echo $card['face'] . $card['suit'] . " ";
        }
        echo "\n";
    }
}

    class Blackjack {
    private $drawDeck;

    public function __construct() {
    $this->drawDeck = new DrawDeck();
    }

    public function startGame() {
    $input = NULL;
    while ( !($input === 0)){
    echo "덱을 다 쓰셨습니다. 새로운 덱을 꺼내옵니다.";
    $deck = new Deck(); // 카드 덱 생성

    while (true) {
    echo "\n -----New Game!------ \n";
    $user = new User($this->drawDeck);
    $dealer = new Dealer($this->drawDeck);

    // 유저와 딜러가 각각 2장의 카드를 받음
    $user->drawCard();
    $dealer->drawCard();
    $user->drawCard();
    $dealer->drawCard();

    echo "카드를 더 받으시겠습니까? (1: Yes, 2: No, 0: Quit) \n";
    fscanf(STDIN, "%d", $input);
    echo "\n";
    if ($input === 0) {
    break;
    } else if ($input === 1) {
    $user->drawCard();
    $userValue = $user->calculateHandValue();
    if ($userValue > 21) {
    echo "유저 패배! 카드의 합이 21을 초과했습니다.\n";
    }

    } else if ($input === 2) {
    $userValue = $user->calculateHandValue();
    $dealerValue = $dealer->calculateHandValue();

    if ($dealerValue >= 17) {
    while ($dealerValue < $userValue && $dealerValue < 21) { $dealer->drawCard();
        $dealerValue = $dealer->calculateHandValue();
        }
        }

        if ($dealerValue > 21 || $userValue > $dealerValue) {
        echo "유저 승리! 카드의 합이 더 높습니다.\n";
        echo "딜러 카드 합 : " . $dealerValue . " 유저 카드 합 : " . $userValue;
        } else if ($userValue === $dealerValue) {
        $userCards = count($user->getHand());
        $dealerCards = count($dealer->getHand());
        if ($userCards < $dealerCards) { echo "유저 승리! 카드의 수가 적습니다.\n" ; echo "딜러 카드 합 : " . $dealerValue . " 유저 카드 합 : " . $userValue; } else if ($userCards> $dealerCards) {
            echo "딜러 승리! 카드의 수가 적습니다.\n";
            echo "딜러 카드 합 : " . $dealerValue . " 유저 카드 합 : " . $userValue;
            } else {
            echo "무승부! 카드의 수와 합이 같습니다.\n";
            echo "딜러 카드 합 : " . $dealerValue . " 유저 카드 합 : " . $userValue;
            }
            }else {
                        echo "딜러 승리!\n";
                        echo "딜러 카드 합 : " . $dealerValue . " 유저 카드 합 : " . $userValue;
                    }
                }
            }
        }
    }
        public function reset() {
            $this->deck = new Deck();
            $this->deck->shuffle();
            $this->user = new Player();
            $this->dealer = new Player();
        }
    }

    $game = new Blackjack();
    $game->start();


            ?>