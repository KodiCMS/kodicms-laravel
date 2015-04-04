<?php namespace KodiCMS\CMS\Helpers;

class Text
{
	/**
	 * @param srtring $string
	 * @return srtring
	 */
	public static function translit($string)
	{
		$rus = ['А', 'Б', 'В', 'Г', 'Д', 'Е', 'Ё', 'Ж', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'П', 'Р', 'С', 'Т', 'У', 'Ф', 'Х', 'Ц', 'Ч', 'Ш', 'Щ', 'Ъ', 'Ы', 'Ь', 'Э', 'Ю', 'Я', 'а', 'б', 'в', 'г', 'д', 'е', 'ё', 'ж', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф', 'х', 'ц', 'ч', 'ш', 'щ', 'ъ', 'ы', 'ь', 'э', 'ю', 'я'];
		$lat = ['A', 'B', 'V', 'G', 'D', 'E', 'E', 'Gh', 'Z', 'I', 'Y', 'K', 'L', 'M', 'N', 'O', 'P', 'R', 'S', 'T', 'U', 'F', 'H', 'C', 'Ch', 'Sh', 'Sch', 'Y', 'Y', 'Y', 'E', 'Yu', 'Ya', 'a', 'b', 'v', 'g', 'd', 'e', 'e', 'gh', 'z', 'i', 'y', 'k', 'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'u', 'f', 'h', 'c', 'ch', 'sh', 'sch', 'y', 'y', 'y', 'e', 'yu', 'ya'];

		return str_replace($rus, $lat, $string);
	}

	/**
	 *
	 * @param string $word
	 * @param array $words
	 * @return array
	 */
	public static function similarWord($word, array $words)
	{
		$similarity = config('pages.similar.similarity');
		$metaSimilarity = 0;
		$minLevenshtein = 1000;
		$metaMinLevenshtein = 1000;

		$result = [];
		$metaResult = [];

		foreach ($words as $n) {
			$minLevenshtein = min($minLevenshtein, levenshtein($n, $word));
		}

		foreach ($words as $n => $k) {
			if (levenshtein($k, $word) <= $minLevenshtein) {
				if (similar_text($k, $word) >= $similarity) {
					$result[$n] = $k;
				}
			}
		}

		foreach ($result as $n) {
			$metaMinLevenshtein = min($metaMinLevenshtein, levenshtein(metaphone($n), metaphone($word)));
		}

		foreach ($result as $n) {
			if (levenshtein($n, $word) == $metaMinLevenshtein) {
				$metaSimilarity = max($metaSimilarity, similar_text(metaphone($n), metaphone($word)));
			}
		}
		
		foreach ($result as $n => $k) {
			if (levenshtein(metaphone($k), metaphone($word)) <= $metaMinLevenshtein) {
				if (similar_text(metaphone($k), metaphone($word)) >= $metaSimilarity) {
					$metaResult[$n] = $k;
				}
			}
		}

		return $metaResult;
	}
}