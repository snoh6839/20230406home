<?php
// 랜덤 숫자 생성
$numbers = array();
while (count($numbers) < 3) {
  $number = rand(8, 9);
  if (!in_array($number, $numbers)) {
    array_push($numbers, $number);
  }
}

// 게임 시작
$strike = 0;
$ball = 0;
$attempts = 0;

echo "야구 게임을 시작합니다!\n";

while ($strike < 3) {
  $attempts++;

  // 사용자 입력 받기
  $input = readline("{$attempts}번째 시도: ");
  $guess = str_split($input);

  // 스트라이크와 볼 판정하기
  $strike = 0;
  $ball = 0;
  for ($i = 0; $i < count($guess); $i++) {
    if ($guess[$i] == $numbers[$i]) {
      $strike++;
    } elseif (in_array($guess[$i], $numbers)) {
      $ball++;
    }
  }

  // 결과 출력하기
  if ($strike == 3) {
    echo "홈런!\n";
  } elseif ($strike > 0 || $ball > 0) {
    echo "{$strike}스트라이크 {$ball}볼\n";
  } else {
    echo "아웃!\n";
  }
}

echo "{$attempts}번째 시도에 성공했습니다!\n";
