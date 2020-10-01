<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Plugin strings are defined here.
 *
 * @package     local_quizpreset
 * @category    string
 * @copyright   2019 Devlion <info@devlion.co>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['pluginname'] = 'Quiz preset';

$string['preview'] = 'תצוגה מקדימה';
$string['edit'] = 'עריכת השאלות במשימה';
$string['numattempt'] = 'תוצאות {$a} תלמידים';
$string['manuallymarking'] = 'מתן ציון לשאלות פתוחות';
$string['settings'] = 'הגדרות';
$string['teachersdiscourse'] = 'שיח מורים: {$a}';

$string['describe_physics_1'] = 'המשימה הנוקשה ביותר בה התלמיד מגיש את המשימה מבלי לקבל משוב על ביצועיו. התלמיד מקבל הזדמנות אחת בלבד לביצוע המשימה כאשר ניתן להגדיר מועד לסיום המבדק.';
$string['describe_physics_2'] = 'במשימה לא יינתן ציון לתלמידים. במהלך המשימה לאחר הגשת תשובתם לשאלה, התלמידים יראו את התשובה הנכונה ויוכלו לתקן את תשובתם בכדי להשלים את המשימה בהצלחה.';
$string['describe_physics_3'] = 'במשימה לא יינתן ציון לתלמידים. במהלך המשימה לאחר הגשת תשובתם לשאלה, התלמידים יקבלו משוב האם תשובתם נכונה או לא אך התשובה הנכונה תתגלה רק בסיום המשימה. התלמידים רשאים להגיש את המשימה מספר פעמים ללא הגבלה.';
$string['describe_physics_4'] = 'במשימה יינתן ציון לתלמידים. במהלך המשימה לאחר הגשת תשובתם לשאלה, התלמידים יקבלו משוב האם תשובתם נכונה או לא אך התשובה הנכונה תתגלה רק בסיום המשימה. כל תשובה שגויה במהלך ניסיון המענה גוררת הורדה בציון כאשר הציון הקובע לתלמידים הוא ניסיון המענה הראשון על המשימה.';
$string['describe_physics_5'] = 'משימה מותאמת אישית.';
$string['describe_physics_6'] = 'במשימה לא יינתן ציון לתלמידים. במהלך המשימה לאחר הגשת תשובתם לשאלה, התלמידים יראו את התשובה הנכונה ויוכלו לתקן את תשובתם בכדי להשלים את המשימה בהצלחה.';

$string['describe_chemistry_1'] = 'התלמידים מקבלים ניסיון מענה אחד בלבד על בוחן. הם עונים על כל שאלה בנפרד. ניתן לענות 3 פעמים על כל שאלה כאשר על כל ניסיון מענה ניתן קנס של שליש מערך השאלה. התשובה הנכונה נחשפת מיד עם סיום השאלה. אך התלמידים לא יוכלו לצפות בתשובות הנכונות שנית אלא רק לאחר תאריך סיום הבוחן - מחייב קביעת תאריך סיום לבוחן.';
$string['describe_chemistry_2'] = 'לתלמיד יש ניסיון אחד. בסיומו, הוא יראה את הנקודות שקיבל ואת השאלות בהן טעה, אך לא את התשובה הנכונה. התשובות יחשפו לתלמידים לאחר תאריך סיום הבוחן - מחייב קביעת תאריך סיום לבוחן.';
$string['describe_chemistry_3'] = 'אין הגבלה במספר הניסיונות, השאלות מופיעות זו אחר זו במרוכז, התלמיד רואה את הטעויות שלו, את הציון ואת התשובה הנכונה כבר בסיום המענה בניסיון הראשון. מתאים לתרגול לפני מבחן של בחנים שכבר נעשו בפורמט שיעורי בית. ציוני בוחן זה, לא יכנסו לדף הציונים של התלמיד. (מומלץ לשכפל את הבחנים כדי לשמור את הציונים המקוריים)';
$string['describe_chemistry_4'] = 'בשיעורי בית, התלמיד מקבל 3 ניסיונות למענה על הבוחן, הציון הוא הציון הגבוה ביותר מבין הניסיונות שנעשו (מקטין את הסיכוי להעתקות בשיעורי בית).. השאלות מופיעות זו אחר זו במרוכז. לאחר הגשת הבוחן, התלמיד רואה את הטעויות שעשה ואת הציון אך לא התשובה הנכונה. כל ניסיון התלמיד מתבסס על קודמו, התלמידים יוכלו לצפות בתשובות הנכונות לאחר תאריך סיום הבוחן - מחייב קביעת תאריך סיום לבוחן.';
$string['describe_chemistry_6'] = 'בבוחן דיאגנוסטי התלמיד יענה על כל השאלות בבוחן ואז ישלח את הבוחן. לתלמיד יש ניסיון מענה אחד, בסיומו, הוא יראה את הנקודות שקיבל, את השאלות בהן טעה ומשוב עבור כל תשובה. התשובות יחשפו לתלמיד לאחר סיום הבוחן - מחייב קביעת תאריך סיום לבוחן.';

$string['describe_biology_1'] = 'המשימה הנוקשה ביותר בה התלמיד מגיש את המשימה מבלי לקבל משוב על ביצועיו. התלמיד מקבל הזדמנות אחת בלבד לביצוע המשימה כאשר ניתן להגדיר מועד לסיום המבדק.';
$string['describe_biology_2'] = 'במשימה לא יינתן ציון לתלמידים. במהלך המשימה לאחר הגשת תשובתם לשאלה, התלמידים יראו את התשובה הנכונה ויוכלו לתקן את תשובתם בכדי להשלים את המשימה בהצלחה.';
$string['describe_biology_3'] = 'במשימה לא יינתן ציון לתלמידים. במהלך המשימה לאחר הגשת תשובתם לשאלה, התלמידים יקבלו משוב האם תשובתם נכונה או לא אך התשובה הנכונה תתגלה רק בסיום המשימה. התלמידים רשאים להגיש את המשימה מספר פעמים ללא הגבלה.';
$string['describe_biology_4'] = 'במשימה יינתן ציון לתלמידים. במהלך המשימה לאחר הגשת תשובתם לשאלה, התלמידים יקבלו משוב האם תשובתם נכונה או לא אך התשובה הנכונה תתגלה רק בסיום המשימה. כל תשובה שגויה במהלך ניסיון המענה גוררת הורדה בציון כאשר הציון הקובע לתלמידים הוא ניסיון המענה הראשון על המשימה.';
$string['describe_biology_5'] = 'משימה מותאמת אישית.';
$string['describe_biology_6'] = 'במשימה לא יינתן ציון לתלמידים. במהלך המשימה לאחר הגשת תשובתם לשאלה, התלמידים יראו את התשובה הנכונה ויוכלו לתקן את תשובתם בכדי להשלים את המשימה בהצלחה.';

$string['name_physics_1'] = 'מבדק';
$string['name_physics_2'] = 'פעילות';
$string['name_physics_3'] = 'תרגול';
$string['name_physics_4'] = 'תרגול עם ציון';
$string['name_physics_5'] = 'מותאם אישית';
$string['name_physics_6'] = 'דיאגנוסטי';

$string['name_chemistry_1'] = 'ש"ב עם רמזים';
$string['name_chemistry_2'] = 'מבחן';
$string['name_chemistry_3'] = 'תרגול עצמי';
$string['name_chemistry_4'] = 'שיעורי בית';
$string['name_chemistry_5'] = 'מותאם אישית';
$string['name_chemistry_6'] = 'דיאגנוסטי';

$string['name_biology_1'] = 'מבדק';
$string['name_biology_2'] = 'פעילות';
$string['name_biology_3'] = 'תרגול';
$string['name_biology_4'] = 'תרגול עם ציון';
$string['name_biology_5'] = 'מותאם אישית';
$string['name_biology_6'] = 'דיאגנוסטי';

$string['intro_physics_1'] = '';
$string['intro_physics_2'] = '';
$string['intro_physics_3'] = '';
$string['intro_physics_4'] = '';
$string['intro_physics_5'] = '';
$string['intro_physics_6'] = '';

$string['intro_chemistry_1'] = '';
$string['intro_chemistry_2'] = '';
$string['intro_chemistry_3'] = '';
$string['intro_chemistry_4'] = '';
$string['intro_chemistry_5'] = '';
$string['intro_chemistry_6'] = '';

$string['intro_biology_1'] = '';
$string['intro_biology_2'] = '';
$string['intro_biology_3'] = '';
$string['intro_biology_4'] = '';
$string['intro_biology_5'] = '';
$string['intro_biology_6'] = '';

$string['buttonviewall'] = 'התאמות נוספות';
$string['buttonviewcustom'] = 'התאמות בסיסיות';
$string['buttonenablegrades'] = 'חשיפת ציונים';
$string['buttondisablegrades'] = 'הסתרת ציונים';

$string['quiztype_name'] = 'סוג {$a} שם';
$string['quiztype_description'] = 'סוג {$a} תיאור';
$string['quiztype_preset'] = 'מוגדר מראש לסוג {$a}';
$string['numberoftypes'] = 'מספר סוגים';
$string['numberoftypesdesc'] = 'מספר סוגים';
$string['quiztype_title'] = 'סוג {$a}';
$string['quiztype_titledescr'] = 'מוגדר מראש לסוג {$a}';

$string['quiz_preview'] = 'תצוגה מקדימה';
$string['quiz_edit'] = 'ערוך שאלה';
$string['quiz_num_attempt'] = 'Attempts';
$string['quiz_manually_marking'] = 'Manually marking';
$string['quiz_setting'] = 'הגדרות';
$string['state'] = 'מַצָב';
$string['statedesc'] = '';
$string['enabled'] = 'מופעל';
$string['disabled'] = 'השבת';
$string['defaulttype'] = 'סוג ברירת מחדל';
$string['defaulttypedesc'] = '';
$string['quizdefaultsettings'] = 'הגדרות ברירת מחדל';
$string['view_all'] = 'צפה בהכל';
$string['view_default'] = 'הצג ברירת מחדל';
