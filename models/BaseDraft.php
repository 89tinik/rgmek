<?php

namespace app\models;

use yii\db\ActiveRecord;

class BaseDraft extends ActiveRecord
{
    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * @param $idx int
     * @return true|void
     */
    public function removeFile($idx)
    {
        $filesArr = json_decode($this->files, true);
        foreach ($filesArr as &$file) {
            if (array_key_first($file) == $idx) {
                if (is_file($file[$idx])) {
                    unlink($file[$idx]);
                }
                $file[$idx] = '';
            }
        }
        $filesJson = json_encode($filesArr);
        $this->files = $filesJson;
        if ($this->save()) {
            return true;
        }
    }

    protected function getShortName($value)
    {
        $fioArr = explode(" ", $value);
        if (count($fioArr) >= 3) {
            $shortName = mb_substr($fioArr[1], 0, 1) . '.' . mb_substr($fioArr[2], 0, 1) . '. ' . $fioArr[0];
        } else {
            $shortName = $value;
        }
        return $shortName;
    }

    /**
     * Возвращает сумму прописью
     * @author runcore
     */
    public static function num2str($num)
    {
        $nul = 'ноль';
        $ten = [
            ['', 'один', 'два', 'три', 'четыре', 'пять', 'шесть', 'семь', 'восемь', 'девять'],
            ['', 'одна', 'две', 'три', 'четыре', 'пять', 'шесть', 'семь', 'восемь', 'девять']
        ];
        $a20 = ['десять', 'одиннадцать', 'двенадцать', 'тринадцать', 'четырнадцать', 'пятнадцать', 'шестнадцать', 'семнадцать', 'восемнадцать', 'девятнадцать'];
        $tens = [2 => 'двадцать', 'тридцать', 'сорок', 'пятьдесят', 'шестьдесят', 'семьдесят', 'восемьдесят', 'девяносто'];
        $hundred = ['', 'сто', 'двести', 'триста', 'четыреста', 'пятьсот', 'шестьсот', 'семьсот', 'восемьсот', 'девятьсот'];
        $unit = [
            ['копейка', 'копейки', 'копеек', 1],
            ['рубль', 'рубля', 'рублей', 0],
            ['тысяча', 'тысячи', 'тысяч', 1],
            ['миллион', 'миллиона', 'миллионов', 0],
            ['миллиард', 'миллиарда', 'миллиардов', 0]
        ];

        list($rub, $kop) = explode('.', sprintf("%015.2f", floatval($num)));
        $out = [];

        if (intval($rub) > 0) {
            $rub_words = [];
            foreach (str_split($rub, 3) as $uk => $v) {
                if (!intval($v)) continue;
                $uk = sizeof($unit) - $uk - 1;
                $gender = $unit[$uk][3];
                list($i1, $i2, $i3) = array_map('intval', str_split($v, 1));

                $rub_words[] = $hundred[$i1]; // сотни
                if ($i2 > 1) {
                    $rub_words[] = $tens[$i2] . ' ' . $ten[$gender][$i3]; // 20-99
                } else {
                    $rub_words[] = $i2 > 0 ? $a20[$i3] : $ten[$gender][$i3]; // 10-19 | 1-9
                }

                if ($uk > 1) {
                    $rub_words[] = self::morph($v, $unit[$uk][0], $unit[$uk][1], $unit[$uk][2]);
                }
            }

            // Оборачиваем числовую часть в скобки
            $out[] = '(' . trim(implode(' ', $rub_words)) . ')';
        } else {
            $out[] = '(' . $nul . ')';
        }

        $out[] = self::morph(intval($rub), $unit[1][0], $unit[1][1], $unit[1][2]); // рубли
        $out[] = $kop . ' ' . self::morph($kop, $unit[0][0], $unit[0][1], $unit[0][2]); // копейки

        return trim(preg_replace('/ {2,}/', ' ', implode(' ', $out)));
    }

    /**
     * Склоняем словоформу
     */
    public static function morph($n, $f1, $f2, $f5)
    {
        $n = abs(intval($n)) % 100;
        if ($n > 10 && $n < 20) return $f5;
        $n = $n % 10;
        if ($n > 1 && $n < 5) return $f2;
        if ($n == 1) return $f1;
        return $f5;
    }

    public function markLast()
    {
        if ($this->last != 1) {
            if ($oldLast = self::findOne(['user_id' => $this->user_id, 'contract_id' => $this->contract_id, 'last' => 1])) {
                $oldLast->last = 0;
                $oldLast->save();
            }
            $this->last = 1;
            $this->save();
        }
    }


    /**
     * @return array
     */
    public function getNullAttr()
    {
        return array_filter($this->getAttributes(), function ($value) {
            return $value === null;
        });
    }
}