<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Article;

class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $articles = [
            [
                'category' => 'ТИУ сегодня',
                'title' => 'Страница ректора',
                'slug' => 'stranica-rektora',
                'slug_alternative' => null,
                'owner_id' => 1,
                'sort_order' => 1,
                'enabled' => 1
            ],
            [
                'category' => 'ТИУ сегодня',
                'title' => 'История',
                'slug' => 'istoriia',
                'slug_alternative' => null,
                'owner_id' => 1,
                'sort_order' => 2,
                'enabled' => 1
            ],
            [
                'category' => 'ТИУ сегодня',
                'title' => 'Рейтинги',
                'slug' => 'reitingi',
                'slug_alternative' => null,
                'owner_id' => 1,
                'sort_order' => 3,
                'enabled' => 1
            ],
            [
                'category' => 'ТИУ сегодня',
                'title' => 'Цели устойчивого развития',
                'slug' => 'celi-ustoicivogo-razvitiia',
                'slug_alternative' => null,
                'owner_id' => 1,
                'sort_order' => 4,
                'enabled' => 1
            ],
            [
                'category' => 'Музей истории науки и техники Зауралья им. Д.И. Менделеева',
                'title' => 'Главная',
                'slug' => 'glavnaia',
                'slug_alternative' => null,
                'owner_id' => 1,
                'sort_order' => 1,
                'enabled' => 1
            ],
            [
                'category' => 'Музей истории науки и техники Зауралья им. Д.И. Менделеева',
                'title' => 'Основатели музея',
                'slug' => 'osnovateli-muzeia',
                'slug_alternative' => null,
                'owner_id' => 1,
                'sort_order' => 2,
                'enabled' => 1
            ],
            [
                'category' => 'Музей истории науки и техники Зауралья им. Д.И. Менделеева',
                'title' => 'Залы музея',
                'slug' => 'zaly-muzeia',
                'slug_alternative' => null,
                'owner_id' => 1,
                'sort_order' => 3,
                'enabled' => 1
            ],
            [
                'category' => 'Музей истории науки и техники Зауралья им. Д.И. Менделеева',
                'title' => 'Выставки и экспозиции',
                'slug' => 'vystavki-i-ekspozicii',
                'slug_alternative' => null,
                'owner_id' => 1,
                'sort_order' => 4,
                'enabled' => 1
            ],
            [
                'category' => 'Музей истории архитектуры и строительства Тюменского края',
                'title' => 'Главная',
                'slug' => 'glavnaia',
                'slug_alternative' => null,
                'owner_id' => 1,
                'sort_order' => 1,
                'enabled' => 1
            ],
            [
                'category' => 'Музей истории архитектуры и строительства Тюменского края',
                'title' => 'Основатели музея',
                'slug' => 'osnovateli-muzeia',
                'slug_alternative' => null,
                'owner_id' => 1,
                'sort_order' => 2,
                'enabled' => 1
            ],
            [
                'category' => 'Музей истории архитектуры и строительства Тюменского края',
                'title' => 'Залы музея',
                'slug' => 'zaly-muzeia',
                'slug_alternative' => null,
                'owner_id' => 1,
                'sort_order' => 3,
                'enabled' => 1
            ],
            [
                'category' => 'Музей истории архитектуры и строительства Тюменского края',
                'title' => 'Выставки и экспозиции',
                'slug' => 'vystavki-i-ekspozicii',
                'slug_alternative' => null,
                'owner_id' => 1,
                'sort_order' => 4,
                'enabled' => 1
            ],
            [
                'category' => 'Структура',
                'title' => 'Структура',
                'slug' => 'struktura',
                'slug_alternative' => null,
                'owner_id' => 1,
                'sort_order' => 1,
                'enabled' => 1
            ],
            [
                'category' => 'Структура',
                'title' => 'Органы управления',
                'slug' => 'organy-upravleniia',
                'slug_alternative' => null,
                'owner_id' => 1,
                'sort_order' => 2,
                'enabled' => 1
            ],
            [
                'category' => 'Структура',
                'title' => 'Руководство. Педагогический состав',
                'slug' => 'rukovodstvo-pedagogiceskii-sostav',
                'slug_alternative' => null,
                'owner_id' => 1,
                'sort_order' => 3,
                'enabled' => 1
            ],
            [
                'category' => 'Документы',
                'title' => 'Абитуриенту',
                'slug' => 'abiturientu',
                'slug_alternative' => null,
                'owner_id' => 1,
                'sort_order' => 1,
                'enabled' => 1
            ],
            [
                'category' => 'Документы',
                'title' => 'Студенту',
                'slug' => 'studentu',
                'slug_alternative' => null,
                'owner_id' => 1,
                'sort_order' => 2,
                'enabled' => 1
            ],
            [
                'category' => 'Документы',
                'title' => 'Сотруднику',
                'slug' => 'sotrudniku',
                'slug_alternative' => null,
                'owner_id' => 1,
                'sort_order' => 3,
                'enabled' => 1
            ],
            [
                'category' => 'Документы',
                'title' => 'Преподавателю',
                'slug' => 'prepodavateliu',
                'slug_alternative' => null,
                'owner_id' => 1,
                'sort_order' => 4,
                'enabled' => 1
            ],
            [
                'category' => 'Информация для СМИ',
                'title' => 'Фирменный стиль',
                'slug' => 'firmennyi-stil',
                'slug_alternative' => null,
                'owner_id' => 1,
                'sort_order' => 1,
                'enabled' => 1
            ],
            [
                'category' => 'Информация для СМИ',
                'title' => 'База экспертов',
                'slug' => 'baza-ekspertov',
                'slug_alternative' => null,
                'owner_id' => 1,
                'sort_order' => 2,
                'enabled' => 1
            ],
            [
                'category' => 'Информация для СМИ',
                'title' => 'Контакты',
                'slug' => 'kontakty',
                'slug_alternative' => null,
                'owner_id' => 1,
                'sort_order' => 3,
                'enabled' => 1
            ],
            [
                'category' => 'Сведения об образовательной организации',
                'title' => 'Основные сведения',
                'slug' => 'osnovnye-svedeniia',
                'slug_alternative' => null,
                'owner_id' => 1,
                'sort_order' => 1,
                'enabled' => 1
            ],
            [
                'category' => 'Сведения об образовательной организации',
                'title' => 'Документы',
                'slug' => 'dokumenty',
                'slug_alternative' => null,
                'owner_id' => 1,
                'sort_order' => 2,
                'enabled' => 1
            ],
            [
                'category' => 'Сведения об образовательной организации',
                'title' => 'Образовательные стандарты',
                'slug' => 'obrazovatelnye-standarty',
                'slug_alternative' => null,
                'owner_id' => 1,
                'sort_order' => 3,
                'enabled' => 1
            ],
            [
                'category' => 'Сведения об образовательной организации',
                'title' => 'Материально-техническое обеспечение и оснащенность образовательного процесса',
                'slug' => 'materialno-texniceskoe-obespecenie-i-osnashhennost-obrazovatelnogo-processa',
                'slug_alternative' => null,
                'owner_id' => 1,
                'sort_order' => 4,
                'enabled' => 1
            ],
            [
                'category' => 'Сведения об образовательной организации',
                'title' => 'Финансово-хозяйственная деятельность',
                'slug' => 'finansovo-xoziaistvennaia-deiatelnost',
                'slug_alternative' => null,
                'owner_id' => 1,
                'sort_order' => 5,
                'enabled' => 1
            ],
            [
                'category' => 'Сведения об образовательной организации',
                'title' => 'Вакантные места для приема (перевода)',
                'slug' => 'vakantnye-mesta-dlia-priema-perevoda',
                'slug_alternative' => null,
                'owner_id' => 1,
                'sort_order' => 6,
                'enabled' => 1
            ],
            [
                'category' => 'Сведения об образовательной организации',
                'title' => 'Доступная среда',
                'slug' => 'dostupnaia-sreda',
                'slug_alternative' => null,
                'owner_id' => 1,
                'sort_order' => 7,
                'enabled' => 1
            ],
            [
                'category' => 'Сведения об образовательной организации',
                'title' => 'Международное сотрудничество',
                'slug' => 'mezdunarodnoe-sotrudnicestvo',
                'slug_alternative' => null,
                'owner_id' => 1,
                'sort_order' => 8,
                'enabled' => 1
            ],
            [
                'category' => 'Институт архитектуры и дизайна',
                'title' => 'Об институте',
                'slug' => 'ob-institute',
                'slug_alternative' => null,
                'owner_id' => 1,
                'sort_order' => 1,
                'enabled' => 1
            ],
            [
                'category' => 'Институт архитектуры и дизайна',
                'title' => 'Руководство',
                'slug' => 'rukovodstvo',
                'slug_alternative' => null,
                'owner_id' => 1,
                'sort_order' => 2,
                'enabled' => 1
            ],
            [
                'category' => 'Институт архитектуры и дизайна',
                'title' => 'Кафедры',
                'slug' => 'kafedry',
                'slug_alternative' => null,
                'owner_id' => 1,
                'sort_order' => 3,
                'enabled' => 1
            ],
            [
                'category' => 'Институт архитектуры и дизайна',
                'title' => 'Абитуриентам',
                'slug' => 'abiturientam',
                'slug_alternative' => null,
                'owner_id' => 1,
                'sort_order' => 4,
                'enabled' => 1
            ],
            [
                'category' => 'Институт архитектуры и дизайна',
                'title' => 'Студентам',
                'slug' => 'studentam',
                'slug_alternative' => null,
                'owner_id' => 1,
                'sort_order' => 5,
                'enabled' => 1
            ],
            [
                'category' => 'Институт архитектуры и дизайна',
                'title' => 'Контакты',
                'slug' => 'kontakty',
                'slug_alternative' => null,
                'owner_id' => 1,
                'sort_order' => 6,
                'enabled' => 1
            ],
            [
                'category' => 'Строительный институт',
                'title' => 'Об институте',
                'slug' => 'ob-institute',
                'slug_alternative' => null,
                'owner_id' => 1,
                'sort_order' => 1,
                'enabled' => 1
            ],
            [
                'category' => 'Строительный институт',
                'title' => 'Руководство',
                'slug' => 'rukovodstvo',
                'slug_alternative' => null,
                'owner_id' => 1,
                'sort_order' => 2,
                'enabled' => 1
            ],
            [
                'category' => 'Строительный институт',
                'title' => 'Кафедры',
                'slug' => 'kafedry',
                'slug_alternative' => null,
                'owner_id' => 1,
                'sort_order' => 3,
                'enabled' => 1
            ],
            [
                'category' => 'Строительный институт',
                'title' => 'Абитуриентам',
                'slug' => 'abiturientam',
                'slug_alternative' => null,
                'owner_id' => 1,
                'sort_order' => 4,
                'enabled' => 1
            ],
            [
                'category' => 'Строительный институт',
                'title' => 'Студентам',
                'slug' => 'studentam',
                'slug_alternative' => null,
                'owner_id' => 1,
                'sort_order' => 5,
                'enabled' => 1
            ],
            [
                'category' => 'Строительный институт',
                'title' => 'Контакты',
                'slug' => 'kontakty',
                'slug_alternative' => null,
                'owner_id' => 1,
                'sort_order' => 6,
                'enabled' => 1
            ],
            [
                'category' => 'Институт сервиса и отраслевого управления',
                'title' => 'Об институте',
                'slug' => 'ob-institute',
                'slug_alternative' => null,
                'owner_id' => 1,
                'sort_order' => 1,
                'enabled' => 1
            ],
            [
                'category' => 'Институт сервиса и отраслевого управления',
                'title' => 'Руководство',
                'slug' => 'rukovodstvo',
                'slug_alternative' => null,
                'owner_id' => 1,
                'sort_order' => 2,
                'enabled' => 1
            ],
            [
                'category' => 'Институт сервиса и отраслевого управления',
                'title' => 'Кафедры',
                'slug' => 'kafedry',
                'slug_alternative' => null,
                'owner_id' => 1,
                'sort_order' => 3,
                'enabled' => 1
            ],
            [
                'category' => 'Институт сервиса и отраслевого управления',
                'title' => 'Абитуриентам',
                'slug' => 'abiturientam',
                'slug_alternative' => null,
                'owner_id' => 1,
                'sort_order' => 4,
                'enabled' => 1
            ],
            [
                'category' => 'Институт сервиса и отраслевого управления',
                'title' => 'Студентам',
                'slug' => 'studentam',
                'slug_alternative' => null,
                'owner_id' => 1,
                'sort_order' => 5,
                'enabled' => 1
            ],
            [
                'category' => 'Институт сервиса и отраслевого управления',
                'title' => 'Контакты',
                'slug' => 'kontakty',
                'slug_alternative' => null,
                'owner_id' => 1,
                'sort_order' => 6,
                'enabled' => 1
            ],
            [
                'category' => 'Институт транспорта',
                'title' => 'Об институте',
                'slug' => 'ob-institute',
                'slug_alternative' => null,
                'owner_id' => 1,
                'sort_order' => 1,
                'enabled' => 1
            ],
            [
                'category' => 'Институт транспорта',
                'title' => 'Руководство',
                'slug' => 'rukovodstvo',
                'slug_alternative' => null,
                'owner_id' => 1,
                'sort_order' => 2,
                'enabled' => 1
            ],
            [
                'category' => 'Институт транспорта',
                'title' => 'Кафедры',
                'slug' => 'kafedry',
                'slug_alternative' => null,
                'owner_id' => 1,
                'sort_order' => 3,
                'enabled' => 1
            ],
            [
                'category' => 'Институт транспорта',
                'title' => 'Абитуриентам',
                'slug' => 'abiturientam',
                'slug_alternative' => null,
                'owner_id' => 1,
                'sort_order' => 4,
                'enabled' => 1
            ],
            [
                'category' => 'Институт транспорта',
                'title' => 'Студентам',
                'slug' => 'studentam',
                'slug_alternative' => null,
                'owner_id' => 1,
                'sort_order' => 5,
                'enabled' => 1
            ],
            [
                'category' => 'Институт транспорта',
                'title' => 'Контакты',
                'slug' => 'kontakty',
                'slug_alternative' => null,
                'owner_id' => 1,
                'sort_order' => 6,
                'enabled' => 1
            ],
            [
                'category' => 'Институт геологии и нефтегазодобычи',
                'title' => 'Об институте',
                'slug' => 'ob-institute',
                'slug_alternative' => null,
                'owner_id' => 1,
                'sort_order' => 1,
                'enabled' => 1
            ],
            [
                'category' => 'Институт геологии и нефтегазодобычи',
                'title' => 'Руководство',
                'slug' => 'rukovodstvo',
                'slug_alternative' => null,
                'owner_id' => 1,
                'sort_order' => 2,
                'enabled' => 1
            ],
            [
                'category' => 'Институт геологии и нефтегазодобычи',
                'title' => 'Кафедры',
                'slug' => 'kafedry',
                'slug_alternative' => null,
                'owner_id' => 1,
                'sort_order' => 3,
                'enabled' => 1
            ],
            [
                'category' => 'Институт геологии и нефтегазодобычи',
                'title' => 'Абитуриентам',
                'slug' => 'abiturientam',
                'slug_alternative' => null,
                'owner_id' => 1,
                'sort_order' => 4,
                'enabled' => 1
            ],
            [
                'category' => 'Институт геологии и нефтегазодобычи',
                'title' => 'Студентам',
                'slug' => 'studentam',
                'slug_alternative' => null,
                'owner_id' => 1,
                'sort_order' => 5,
                'enabled' => 1
            ],
            [
                'category' => 'Институт геологии и нефтегазодобычи',
                'title' => 'Контакты',
                'slug' => 'kontakty',
                'slug_alternative' => null,
                'owner_id' => 1,
                'sort_order' => 6,
                'enabled' => 1
            ],
            [
                'category' => 'Институт промышленных технологий и инжиниринга',
                'title' => 'Об институте',
                'slug' => 'ob-institute',
                'slug_alternative' => null,
                'owner_id' => 1,
                'sort_order' => 1,
                'enabled' => 1
            ],
            [
                'category' => 'Институт промышленных технологий и инжиниринга',
                'title' => 'Руководство',
                'slug' => 'rukovodstvo',
                'slug_alternative' => null,
                'owner_id' => 1,
                'sort_order' => 2,
                'enabled' => 1
            ],
            [
                'category' => 'Институт промышленных технологий и инжиниринга',
                'title' => 'Кафедры',
                'slug' => 'kafedry',
                'slug_alternative' => null,
                'owner_id' => 1,
                'sort_order' => 3,
                'enabled' => 1
            ],
            [
                'category' => 'Институт промышленных технологий и инжиниринга',
                'title' => 'Абитуриентам',
                'slug' => 'abiturientam',
                'slug_alternative' => null,
                'owner_id' => 1,
                'sort_order' => 4,
                'enabled' => 1
            ],
            [
                'category' => 'Институт промышленных технологий и инжиниринга',
                'title' => 'Студентам',
                'slug' => 'studentam',
                'slug_alternative' => null,
                'owner_id' => 1,
                'sort_order' => 5,
                'enabled' => 1
            ],
            [
                'category' => 'Институт промышленных технологий и инжиниринга',
                'title' => 'Контакты',
                'slug' => 'kontakty',
                'slug_alternative' => null,
                'owner_id' => 1,
                'sort_order' => 6,
                'enabled' => 1
            ],
            [
                'category' => 'Институт дополнительного и дистанционного образования',
                'title' => 'Об институте',
                'slug' => 'ob-institute',
                'slug_alternative' => null,
                'owner_id' => 1,
                'sort_order' => 1,
                'enabled' => 1
            ],
            [
                'category' => 'Институт дополнительного и дистанционного образования',
                'title' => 'Руководство',
                'slug' => 'rukovodstvo',
                'slug_alternative' => null,
                'owner_id' => 1,
                'sort_order' => 2,
                'enabled' => 1
            ],
            [
                'category' => 'Институт дополнительного и дистанционного образования',
                'title' => 'Кафедры',
                'slug' => 'kafedry',
                'slug_alternative' => null,
                'owner_id' => 1,
                'sort_order' => 3,
                'enabled' => 1
            ],
            [
                'category' => 'Институт дополнительного и дистанционного образования',
                'title' => 'Абитуриентам',
                'slug' => 'abiturientam',
                'slug_alternative' => null,
                'owner_id' => 1,
                'sort_order' => 4,
                'enabled' => 1
            ],
            [
                'category' => 'Институт дополнительного и дистанционного образования',
                'title' => 'Студентам',
                'slug' => 'studentam',
                'slug_alternative' => null,
                'owner_id' => 1,
                'sort_order' => 5,
                'enabled' => 1
            ],
            [
                'category' => 'Институт дополнительного и дистанционного образования',
                'title' => 'Контакты',
                'slug' => 'kontakty',
                'slug_alternative' => null,
                'owner_id' => 1,
                'sort_order' => 6,
                'enabled' => 1
            ],
            [
                'category' => 'Высшая инженерная школа EG',
                'title' => 'Об институте',
                'slug' => 'ob-institute',
                'slug_alternative' => null,
                'owner_id' => 1,
                'sort_order' => 1,
                'enabled' => 1
            ],
            [
                'category' => 'Высшая инженерная школа EG',
                'title' => 'Руководство',
                'slug' => 'rukovodstvo',
                'slug_alternative' => null,
                'owner_id' => 1,
                'sort_order' => 2,
                'enabled' => 1
            ],
            [
                'category' => 'Высшая инженерная школа EG',
                'title' => 'Кафедры',
                'slug' => 'kafedry',
                'slug_alternative' => null,
                'owner_id' => 1,
                'sort_order' => 3,
                'enabled' => 1
            ],
            [
                'category' => 'Высшая инженерная школа EG',
                'title' => 'Абитуриентам',
                'slug' => 'abiturientam',
                'slug_alternative' => null,
                'owner_id' => 1,
                'sort_order' => 4,
                'enabled' => 1
            ],
            [
                'category' => 'Высшая инженерная школа EG',
                'title' => 'Студентам',
                'slug' => 'studentam',
                'slug_alternative' => null,
                'owner_id' => 1,
                'sort_order' => 5,
                'enabled' => 1
            ],
            [
                'category' => 'Высшая инженерная школа EG',
                'title' => 'Контакты',
                'slug' => 'kontakty',
                'slug_alternative' => null,
                'owner_id' => 1,
                'sort_order' => 6,
                'enabled' => 1
            ],
            [
                'category' => 'Многопрофильный колледж',
                'title' => 'О колледже',
                'slug' => 'o-kolledze',
                'slug_alternative' => null,
                'owner_id' => 1,
                'sort_order' => 1,
                'enabled' => 1
            ],
            [
                'category' => 'Многопрофильный колледж',
                'title' => 'Дирекция',
                'slug' => 'direkciia',
                'slug_alternative' => null,
                'owner_id' => 1,
                'sort_order' => 2,
                'enabled' => 1
            ],
            [
                'category' => 'Многопрофильный колледж',
                'title' => 'Абитуриентам',
                'slug' => 'abiturientam',
                'slug_alternative' => null,
                'owner_id' => 1,
                'sort_order' => 3,
                'enabled' => 1
            ],
            [
                'category' => 'Многопрофильный колледж',
                'title' => 'Отделения',
                'slug' => 'otdeleniia',
                'slug_alternative' => null,
                'owner_id' => 1,
                'sort_order' => 4,
                'enabled' => 1
            ],
            [
                'category' => 'Многопрофильный колледж',
                'title' => 'Студентам',
                'slug' => 'studentam',
                'slug_alternative' => null,
                'owner_id' => 1,
                'sort_order' => 5,
                'enabled' => 1
            ],
            [
                'category' => 'Многопрофильный колледж',
                'title' => 'Центр компетенций WorldSkills',
                'slug' => 'centr-kompetencii-worldskills',
                'slug_alternative' => null,
                'owner_id' => 1,
                'sort_order' => 6,
                'enabled' => 1
            ],
            [
                'category' => 'Многопрофильный колледж',
                'title' => 'Контакты',
                'slug' => 'kontakty',
                'slug_alternative' => null,
                'owner_id' => 1,
                'sort_order' => 7,
                'enabled' => 1
            ],
            [
                'category' => 'Ноябрьский институт нефти и газа — филиал ТИУ в г.Ноябрьск',
                'title' => 'Об институте',
                'slug' => 'ob-institute',
                'slug_alternative' => null,
                'owner_id' => 1,
                'sort_order' => 1,
                'enabled' => 1
            ],
            [
                'category' => 'Ноябрьский институт нефти и газа — филиал ТИУ в г.Ноябрьск',
                'title' => 'Руководство',
                'slug' => 'rukovodstvo',
                'slug_alternative' => null,
                'owner_id' => 1,
                'sort_order' => 2,
                'enabled' => 1
            ],
            [
                'category' => 'Ноябрьский институт нефти и газа — филиал ТИУ в г.Ноябрьск',
                'title' => 'Кафедры',
                'slug' => 'abiturientam',
                'slug_alternative' => null,
                'owner_id' => 1,
                'sort_order' => 3,
                'enabled' => 1
            ],
            [
                'category' => 'Ноябрьский институт нефти и газа — филиал ТИУ в г.Ноябрьск',
                'title' => 'Абитуриентам',
                'slug' => '',
                'slug_alternative' => null,
                'owner_id' => 1,
                'sort_order' => 4,
                'enabled' => 1
            ],
            [
                'category' => 'Ноябрьский институт нефти и газа — филиал ТИУ в г.Ноябрьск',
                'title' => 'Студентам',
                'slug' => 'studentam',
                'slug_alternative' => null,
                'owner_id' => 1,
                'sort_order' => 5,
                'enabled' => 1
            ],
            [
                'category' => 'Ноябрьский институт нефти и газа — филиал ТИУ в г.Ноябрьск',
                'title' => 'Контакты',
                'slug' => 'kontakty',
                'slug_alternative' => null,
                'owner_id' => 1,
                'sort_order' => 6,
                'enabled' => 1
            ],
            [
                'category' => 'Тобольский индустриальный институт — филиал ТИУ в г.Тобольск',
                'title' => 'Об институте',
                'slug' => 'ob-institute',
                'slug_alternative' => null,
                'owner_id' => 1,
                'sort_order' => 1,
                'enabled' => 1
            ],
            [
                'category' => 'Тобольский индустриальный институт — филиал ТИУ в г.Тобольск',
                'title' => 'Руководство',
                'slug' => 'rukovodstvo',
                'slug_alternative' => null,
                'owner_id' => 1,
                'sort_order' => 2,
                'enabled' => 1
            ],
            [
                'category' => 'Тобольский индустриальный институт — филиал ТИУ в г.Тобольск',
                'title' => 'Кафедры',
                'slug' => 'abiturientam',
                'slug_alternative' => null,
                'owner_id' => 1,
                'sort_order' => 3,
                'enabled' => 1
            ],
            [
                'category' => 'Тобольский индустриальный институт — филиал ТИУ в г.Тобольск',
                'title' => 'Абитуриентам',
                'slug' => '',
                'slug_alternative' => null,
                'owner_id' => 1,
                'sort_order' => 4,
                'enabled' => 1
            ],
            [
                'category' => 'Тобольский индустриальный институт — филиал ТИУ в г.Тобольск',
                'title' => 'Студентам',
                'slug' => 'studentam',
                'slug_alternative' => null,
                'owner_id' => 1,
                'sort_order' => 5,
                'enabled' => 1
            ],
            [
                'category' => 'Тобольский индустриальный институт — филиал ТИУ в г.Тобольск',
                'title' => 'Контакты',
                'slug' => 'kontakty',
                'slug_alternative' => null,
                'owner_id' => 1,
                'sort_order' => 6,
                'enabled' => 1
            ],
            [
                'category' => 'Сургутский институт нефти и газа — филиал ТИУ в г.Сургут',
                'title' => 'Об институте',
                'slug' => 'ob-institute',
                'slug_alternative' => null,
                'owner_id' => 1,
                'sort_order' => 1,
                'enabled' => 1
            ],
            [
                'category' => 'Сургутский институт нефти и газа — филиал ТИУ в г.Сургут',
                'title' => 'Руководство',
                'slug' => 'rukovodstvo',
                'slug_alternative' => null,
                'owner_id' => 1,
                'sort_order' => 2,
                'enabled' => 1
            ],
            [
                'category' => 'Сургутский институт нефти и газа — филиал ТИУ в г.Сургут',
                'title' => 'Кафедры',
                'slug' => 'abiturientam',
                'slug_alternative' => null,
                'owner_id' => 1,
                'sort_order' => 3,
                'enabled' => 1
            ],
            [
                'category' => 'Сургутский институт нефти и газа — филиал ТИУ в г.Сургут',
                'title' => 'Абитуриентам',
                'slug' => '',
                'slug_alternative' => null,
                'owner_id' => 1,
                'sort_order' => 4,
                'enabled' => 1
            ],
            [
                'category' => 'Сургутский институт нефти и газа — филиал ТИУ в г.Сургут',
                'title' => 'Студентам',
                'slug' => 'studentam',
                'slug_alternative' => null,
                'owner_id' => 1,
                'sort_order' => 5,
                'enabled' => 1
            ],
            [
                'category' => 'Сургутский институт нефти и газа — филиал ТИУ в г.Сургут',
                'title' => 'Контакты',
                'slug' => 'kontakty',
                'slug_alternative' => null,
                'owner_id' => 1,
                'sort_order' => 6,
                'enabled' => 1
            ],
            [
                'category' => 'Филиал ТИУ в г.Нижневартовск',
                'title' => 'Об институте',
                'slug' => 'ob-institute',
                'slug_alternative' => null,
                'owner_id' => 1,
                'sort_order' => 1,
                'enabled' => 1
            ],
            [
                'category' => 'Филиал ТИУ в г.Нижневартовск',
                'title' => 'Руководство',
                'slug' => 'rukovodstvo',
                'slug_alternative' => null,
                'owner_id' => 1,
                'sort_order' => 2,
                'enabled' => 1
            ],
            [
                'category' => 'Филиал ТИУ в г.Нижневартовск',
                'title' => 'Кафедры',
                'slug' => 'abiturientam',
                'slug_alternative' => null,
                'owner_id' => 1,
                'sort_order' => 3,
                'enabled' => 1
            ],
            [
                'category' => 'Филиал ТИУ в г.Нижневартовск',
                'title' => 'Абитуриентам',
                'slug' => '',
                'slug_alternative' => null,
                'owner_id' => 1,
                'sort_order' => 4,
                'enabled' => 1
            ],
            [
                'category' => 'Филиал ТИУ в г.Нижневартовск',
                'title' => 'Студентам',
                'slug' => 'studentam',
                'slug_alternative' => null,
                'owner_id' => 1,
                'sort_order' => 5,
                'enabled' => 1
            ],
            [
                'category' => 'Филиал ТИУ в г.Нижневартовск',
                'title' => 'Контакты',
                'slug' => 'kontakty',
                'slug_alternative' => null,
                'owner_id' => 1,
                'sort_order' => 6,
                'enabled' => 1
            ],
            [
                'category' => 'Лицей',
                'title' => 'О лицее',
                'slug' => 'o-licee',
                'slug_alternative' => null,
                'owner_id' => 1,
                'sort_order' => 1,
                'enabled' => 1
            ],
            [
                'category' => 'Лицей',
                'title' => 'Дирекция',
                'slug' => 'direkciia',
                'slug_alternative' => null,
                'owner_id' => 1,
                'sort_order' => 2,
                'enabled' => 1
            ],
            [
                'category' => 'Лицей',
                'title' => 'Лицеисту',
                'slug' => 'liceistu',
                'slug_alternative' => null,
                'owner_id' => 1,
                'sort_order' => 3,
                'enabled' => 1
            ],
            [
                'category' => 'Лицей',
                'title' => 'Поступающему',
                'slug' => 'postupaiushhemu',
                'slug_alternative' => null,
                'owner_id' => 1,
                'sort_order' => 4,
                'enabled' => 1
            ],
            [
                'category' => 'Лицей',
                'title' => 'Организация образовательной деятельности',
                'slug' => 'organizaciia-obrazovatelnoi-deiatelnosti',
                'slug_alternative' => null,
                'owner_id' => 1,
                'sort_order' => 5,
                'enabled' => 1
            ],
            [
                'category' => 'Лицей',
                'title' => 'Контакты',
                'slug' => 'kontakty',
                'slug_alternative' => null,
                'owner_id' => 1,
                'sort_order' => 6,
                'enabled' => 1
            ],
            [
                'category' => 'Международная деятельность',
                'title' => 'Education at TIU',
                'slug' => 'education-at-tiu',
                'slug_alternative' => null,
                'owner_id' => 1,
                'sort_order' => 1,
                'enabled' => 1
            ],
            [
                'category' => 'Международная деятельность',
                'title' => 'Интерклуб',
                'slug' => 'interklub',
                'slug_alternative' => null,
                'owner_id' => 1,
                'sort_order' => 2,
                'enabled' => 1
            ],
            [
                'category' => 'Международная деятельность',
                'title' => 'Программы двойных дипломов',
                'slug' => 'programmy-dvoinyx-diplomov',
                'slug_alternative' => null,
                'owner_id' => 1,
                'sort_order' => 3,
                'enabled' => 1
            ],
            [
                'category' => 'Международная деятельность',
                'title' => 'Конкурсы, гранты, стипендии',
                'slug' => 'konkursy-granty-stipendii',
                'slug_alternative' => null,
                'owner_id' => 1,
                'sort_order' => 4,
                'enabled' => 1
            ],
            [
                'category' => 'Международная деятельность',
                'title' => 'Контакты',
                'slug' => 'kontakty',
                'slug_alternative' => null,
                'owner_id' => 1,
                'sort_order' => 5,
                'enabled' => 1
            ],
            [
                'category' => 'Международная деятельность',
                'title' => 'Европейское приложение к диплому',
                'slug' => 'evropeiskoe-prilozenie-k-diplomu',
                'slug_alternative' => null,
                'owner_id' => 1,
                'sort_order' => 6,
                'enabled' => 1
            ],
            [
                'category' => 'Международная деятельность',
                'title' => 'Обучение и стажировки за рубежом',
                'slug' => 'obucenie-i-stazirovki-za-rubezom',
                'slug_alternative' => null,
                'owner_id' => 1,
                'sort_order' => 7,
                'enabled' => 1
            ],
            [
                'category' => 'Международная деятельность',
                'title' => 'Международные проекты',
                'slug' => 'mezdunarodnye-proekty',
                'slug_alternative' => null,
                'owner_id' => 1,
                'sort_order' => 8,
                'enabled' => 1
            ],
            [
                'category' => 'Внеучебная деятельность',
                'title' => 'Спорт',
                'slug' => 'sport',
                'slug_alternative' => null,
                'owner_id' => 1,
                'sort_order' => 1,
                'enabled' => 1
            ],
            [
                'category' => 'Внеучебная деятельность',
                'title' => 'Творчество',
                'slug' => 'tvorcestvo',
                'slug_alternative' => null,
                'owner_id' => 1,
                'sort_order' => 2,
                'enabled' => 1
            ],
            [
                'category' => 'Внеучебная деятельность',
                'title' => 'Объединенный совет обучающихся',
                'slug' => 'obieedinennyi-sovet-obucaiushhixsia',
                'slug_alternative' => null,
                'owner_id' => 1,
                'sort_order' => 3,
                'enabled' => 1
            ],
            [
                'category' => 'Внеучебная деятельность',
                'title' => 'Молодежные инициативы',
                'slug' => 'molodeznye-iniciativy',
                'slug_alternative' => null,
                'owner_id' => 1,
                'sort_order' => 4,
                'enabled' => 1
            ],
            [
                'category' => 'Внеучебная деятельность',
                'title' => 'Штаб студенческих отрядов',
                'slug' => 'stab-studenceskix-otriadov',
                'slug_alternative' => null,
                'owner_id' => 1,
                'sort_order' => 5,
                'enabled' => 1
            ],
            [
                'category' => 'Внеучебная деятельность',
                'title' => 'Студенческая инновационная платформая',
                'slug' => 'studenceskaia-innovacionnaia-platformaia',
                'slug_alternative' => null,
                'owner_id' => 1,
                'sort_order' => 6,
                'enabled' => 1
            ],
            [
                'category' => 'Внеучебная деятельность',
                'title' => 'Локально-нормативная документация',
                'slug' => 'lokalno-normativnaia-dokumentaciia',
                'slug_alternative' => null,
                'owner_id' => 1,
                'sort_order' => 7,
                'enabled' => 1
            ],
            [
                'category' => 'Студенческий городок',
                'title' => 'Абитуриентам',
                'slug' => 'abiturientam',
                'slug_alternative' => null,
                'owner_id' => 1,
                'sort_order' => 1,
                'enabled' => 1
            ],
            [
                'category' => 'Студенческий городок',
                'title' => 'Студентам',
                'slug' => 'studentam',
                'slug_alternative' => null,
                'owner_id' => 1,
                'sort_order' => 2,
                'enabled' => 1
            ],
            [
                'category' => 'Студенческий городок',
                'title' => 'Заселение 2022',
                'slug' => 'zaselenie-2022',
                'slug_alternative' => null,
                'owner_id' => 1,
                'sort_order' => 3,
                'enabled' => 1
            ],
            [
                'category' => 'Студенческий городок',
                'title' => 'Центр учета проживающих',
                'slug' => 'centr-uceta-prozivaiushhix',
                'slug_alternative' => null,
                'owner_id' => 1,
                'sort_order' => 4,
                'enabled' => 1
            ],
            [
                'category' => 'Студенческий городок',
                'title' => 'Дирекция',
                'slug' => 'direkciia',
                'slug_alternative' => null,
                'owner_id' => 1,
                'sort_order' => 5,
                'enabled' => 1
            ],
            [
                'category' => 'Студенческий городок',
                'title' => 'Общежития',
                'slug' => 'obshhezitiia',
                'slug_alternative' => null,
                'owner_id' => 1,
                'sort_order' => 6,
                'enabled' => 1
            ],
            [
                'category' => 'Студенческий городок',
                'title' => 'Контакты',
                'slug' => 'kontakty',
                'slug_alternative' => null,
                'owner_id' => 1,
                'sort_order' => 7,
                'enabled' => 1
            ],
            [
                'category' => 'Студенческий городок',
                'title' => 'Сведения по оплате за проживание',
                'slug' => 'svedeniia-po-oplate-za-prozivanie',
                'slug_alternative' => null,
                'owner_id' => 1,
                'sort_order' => 8,
                'enabled' => 1
            ],
            [
                'category' => 'Студенческий городок',
                'title' => 'Центр организации активности обучающихся',
                'slug' => 'centr-organizacii-aktivnosti-obucaiushhixsia',
                'slug_alternative' => null,
                'owner_id' => 1,
                'sort_order' => 9,
                'enabled' => 1
            ],
            [
                'category' => 'Студенческий городок',
                'title' => 'Образцы заявлений на общежитие',
                'slug' => 'obrazcy-zaiavlenii-na-obshhezitie',
                'slug_alternative' => null,
                'owner_id' => 1,
                'sort_order' => 10,
                'enabled' => 1
            ],
            [
                'category' => 'Студенческий городок',
                'title' => 'Порядок заселения, проживания, выселения в общежития ТИУ',
                'slug' => 'poriadok-zaseleniia-prozivaniia-vyseleniia-v-obshhezitiia-tiu',
                'slug_alternative' => null,
                'owner_id' => 1,
                'sort_order' => 11,
                'enabled' => 1
            ],
            [
                'category' => 'Социально-психологическое сопровождение',
                'title' => 'Психологическая поддержка',
                'slug' => 'psixologiceskaia-podderzka',
                'slug_alternative' => null,
                'owner_id' => 1,
                'sort_order' => 1,
                'enabled' => 1
            ],
            [
                'category' => 'Социально-психологическое сопровождение',
                'title' => 'Социальные гарантии',
                'slug' => 'socialnye-garantii',
                'slug_alternative' => null,
                'owner_id' => 1,
                'sort_order' => 2,
                'enabled' => 1
            ],
            [
                'category' => 'Социально-психологическое сопровождение',
                'title' => 'Профилактическая работа',
                'slug' => 'profilakticeskaia-rabota',
                'slug_alternative' => null,
                'owner_id' => 1,
                'sort_order' => 3,
                'enabled' => 1
            ],
            [
                'category' => 'Социально-психологическое сопровождение',
                'title' => 'Инклюзивное образование',
                'slug' => 'inkliuzivnoe-obrazovanie',
                'slug_alternative' => null,
                'owner_id' => 1,
                'sort_order' => 4,
                'enabled' => 1
            ],
            [
                'category' => 'Социально-психологическое сопровождение',
                'title' => 'Кураторская работа',
                'slug' => 'kuratorskaia-rabota',
                'slug_alternative' => null,
                'owner_id' => 1,
                'sort_order' => 5,
                'enabled' => 1
            ],
            [
                'category' => 'Социально-психологическое сопровождение',
                'title' => 'Конкурс “Куратор года”',
                'slug' => 'konkurs-kurator-goda',
                'slug_alternative' => null,
                'owner_id' => 1,
                'sort_order' => 6,
                'enabled' => 1
            ],
            [
                'category' => 'Молодежный бизнес-инкубатор',
                'title' => 'Главная',
                'slug' => 'glavnaia',
                'slug_alternative' => null,
                'owner_id' => 1,
                'sort_order' => 1,
                'enabled' => 1
            ],
            [
                'category' => 'Молодежный бизнес-инкубатор',
                'title' => 'Отбор резидентов',
                'slug' => 'otbor-rezidentov',
                'slug_alternative' => null,
                'owner_id' => 1,
                'sort_order' => 2,
                'enabled' => 1
            ],
            [
                'category' => 'Молодежный бизнес-инкубатор',
                'title' => 'Программа «Стартап как диплом»',
                'slug' => 'programma-startap-kak-diplom',
                'slug_alternative' => null,
                'owner_id' => 1,
                'sort_order' => 3,
                'enabled' => 1
            ],
            [
                'category' => 'Молодежный бизнес-инкубатор',
                'title' => 'Конкурс бизнес-идей «Биржа идей»',
                'slug' => 'konkurs-biznes-idei-birza-idei',
                'slug_alternative' => null,
                'owner_id' => 1,
                'sort_order' => 4,
                'enabled' => 1
            ],
        ];

        foreach($articles as $article) {
            $title = $article['category'];
            $category = Category::where('name', '=', $title)
                ->orWhere('name', 'LIKE', "%$title%")
                ->first();

            if(!Article::where([
                    ['title', '=', $article['title']],
                    ['category_id', '=', $category->id ?? null]
                ])
                ->first()
            ) {
                $category->articles()->create([
                    'title' => $article['title'],
                    'slug' => $article['slug'],
                    'slug_alternative' => $article['slug_alternative'],
                    'owner_id' => $article['owner_id'],
                    'sort_order' => $article['sort_order'],
                    'enabled' => $article['enabled']
                ]);
            }
        }
    }
}
