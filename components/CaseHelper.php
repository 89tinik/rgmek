<?php

namespace app\components;

class CaseHelper
{
    /**
     * Словарь склонений
     */
    private static $dictionary = [
        'собственник' => ['собственника', 'собственнику'],
        'директор' => ['директора', 'директору'],
        'ио директора' => ['и. о. директора', 'и. о. директора'],
        'и.о. директора' => ['и. о. директора', 'и. о. директора'],
        'и о директора' => ['и. о. директора', 'и. о. директора'],
        'и. о. директора' => ['и. о. директора', 'и. о. директора'],
        'генеральный директор' => ['генерального директора', 'генеральному директору'],
        'устав' => ['устава', 'уставу'],
        'договор' => ['договора', 'договору'],
    ];

    /**
     * Возвращает слово в нужном падеже
     *
     * @param string $word Исходное слово (именительный падеж)
     * @param int $caseNum Номер падежа (1 — родительный, 2 — дательный)
     * @return string
     */
    public static function getCase(string $word, int $caseNum)
    {
        $wordLower = mb_strtolower($word);
        if (isset(self::$dictionary[$wordLower]) && in_array($caseNum, [1, 2])) {
            return self::$dictionary[$wordLower][$caseNum - 1];
        }
        return $word;
    }

    /**
     * Возвращает фамилию и инициалы
     *
     * @param string $word
     * @return string
     */
    public static function getInitials(string $word): string
    {
        $wordArr = array_filter(explode(' ', trim($word)));
        $output = '';

        if (isset($wordArr[1])) {
            $output .= mb_substr($wordArr[1], 0, 1) . '.';
        }
        if (isset($wordArr[2])) {
            $output .= mb_substr($wordArr[2], 0, 1) . '. ';
        }

        return $output . $wordArr[0];
    }

    /**
     * @param $string
     * @return string
     */
    public static function ucfirstCyrillic($string)
    {
        return mb_strtoupper(mb_substr($string, 0, 1)) . mb_substr($string, 1);
    }
}