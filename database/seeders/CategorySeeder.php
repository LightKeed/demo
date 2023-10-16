<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            [
                'name' => 'Университет',
                'slug' => 'university',
                'slug_alternative' => null,
                'parent_id' => null,
                'sort_order' => 1,
                'enabled' => 1
            ],
            [
                'name' => 'Поступление',
                'slug' => 'postuplenie',
                'slug_alternative' => null,
                'parent_id' => null,
                'sort_order' => 2,
                'enabled' => 1
            ],
            [
                'name' => 'Наука',
                'slug' => 'nauka',
                'slug_alternative' => null,
                'parent_id' => null,
                'sort_order' => 3,
                'enabled' => 1
            ],
            [
                'name' => 'Образование',
                'slug' => 'obrazovanie',
                'slug_alternative' => null,
                'parent_id' => null,
                'sort_order' => 4,
                'enabled' => 1
            ],
            [
                'name' => 'Внеучебная деятельность',
                'slug' => 'vneucebnaia-deiatelnost',
                'slug_alternative' => null,
                'parent_id' => null,
                'sort_order' => 4,
                'enabled' => 1
            ],
            [
                'name' => 'Карьера',
                'slug' => 'karera',
                'slug_alternative' => null,
                'parent_id' => null,
                'sort_order' => 5,
                'enabled' => 1
            ],
            [
                'name' => 'Контакты',
                'slug' => 'kontakty',
                'slug_alternative' => null,
                'parent_id' => null,
                'sort_order' => 6,
                'enabled' => 1
            ],
            [
                'name' => 'ТИУ сегодня',
                'slug' => 'tiu-segodnia',
                'slug_alternative' => null,
                'parent_id' => 'Университет',
                'sort_order' => 1,
                'enabled' => 1
            ],
            [
                'name' => 'Музеи',
                'slug' => 'muzei',
                'slug_alternative' => null,
                'parent_id' => 'Университет',
                'sort_order' => 2,
                'enabled' => 1
            ],
            [
                'name' => 'Структура',
                'slug' => 'struktura',
                'slug_alternative' => null,
                'parent_id' => 'Университет',
                'sort_order' => 3,
                'enabled' => 1
            ],
            [
                'name' => 'Документы',
                'slug' => 'dokumenty',
                'slug_alternative' => null,
                'parent_id' => 'Университет',
                'sort_order' => 4,
                'enabled' => 1
            ],
            [
                'name' => 'Информация для СМИ',
                'slug' => 'informaciia-dlia-smi',
                'slug_alternative' => null,
                'parent_id' => 'Университет',
                'sort_order' => 5,
                'enabled' => 1
            ],
            [
                'name' => 'Сведения об образовательной организации',
                'slug' => 'svedeniia-ob-obrazovatelnoi-organizacii',
                'slug_alternative' => null,
                'parent_id' => 'Университет',
                'sort_order' => 6,
                'enabled' => 1
            ],
            [
                'name' => 'Музей истории науки и техники Зауралья им. Д.И. Менделеева',
                'slug' => 'muzei-istorii-nauki-i-texniki-zauralia-im-di-mendeleeva',
                'slug_alternative' => null,
                'parent_id' => 'Музеи',
                'sort_order' => 1,
                'enabled' => 1
            ],
            [
                'name' => 'Музей истории архитектуры и строительства Тюменского края',
                'slug' => 'muzei-istorii-arxitektury-i-stroitelstva-tiumenskogo-kraia',
                'slug_alternative' => null,
                'parent_id' => 'Музеи',
                'sort_order' => 2,
                'enabled' => 1
            ],
            [
                'name' => 'Институты',
                'slug' => 'instituty',
                'slug_alternative' => null,
                'parent_id' => 'Образование',
                'sort_order' => 1,
                'enabled' => 1
            ],
            [
                'name' => 'Многопрофильный колледж',
                'slug' => 'mnogoprofilnyi-kolledz',
                'slug_alternative' => null,
                'parent_id' => 'Образование',
                'sort_order' => 2,
                'enabled' => 1
            ],
            [
                'name' => 'Лицей',
                'slug' => 'licei',
                'slug_alternative' => null,
                'parent_id' => 'Образование',
                'sort_order' => 3,
                'enabled' => 1
            ],
            [
                'name' => 'Филиалы',
                'slug' => 'filialy',
                'slug_alternative' => null,
                'parent_id' => 'Образование',
                'sort_order' => 4,
                'enabled' => 1
            ],
            [
                'name' => 'Учебная деятельность',
                'slug' => 'ucebnaia-deiatelnost',
                'slug_alternative' => null,
                'parent_id' => 'Образование',
                'sort_order' => 5,
                'enabled' => 1
            ],
            [
                'name' => 'Международная деятельность',
                'slug' => 'mezdunarodnaia-deiatelnost',
                'slug_alternative' => null,
                'parent_id' => 'Образование',
                'sort_order' => 6,
                'enabled' => 1
            ],
            [
                'name' => 'Индивидуальные образовательные траектории',
                'slug' => 'individualnye-obrazovatelnye-traektorii',
                'slug_alternative' => null,
                'parent_id' => 'Образование',
                'sort_order' => 7,
                'enabled' => 1
            ],
            [
                'name' => 'Платные образовательные услуги',
                'slug' => 'platnye-obrazovatelnye-uslugi',
                'slug_alternative' => null,
                'parent_id' => 'Образование',
                'sort_order' => 8,
                'enabled' => 1
            ],
            [
                'name' => 'Стипендиии и меры поддержки',
                'slug' => 'stipendiii-i-mery-podderzki',
                'slug_alternative' => null,
                'parent_id' => 'Образование',
                'sort_order' => 9,
                'enabled' => 1
            ],
            [
                'name' => 'Кампус',
                'slug' => 'kampus',
                'slug_alternative' => null,
                'parent_id' => 'Образование',
                'sort_order' => 10,
                'enabled' => 1
            ],
            [
                'name' => 'Институт архитектуры и дизайна',
                'slug' => 'institut-arxitektury-i-dizaina',
                'slug_alternative' => null,
                'parent_id' => 'Институты',
                'sort_order' => 1,
                'enabled' => 1
            ],
            [
                'name' => 'Строительный институт',
                'slug' => 'stroitelnyi-institut',
                'slug_alternative' => null,
                'parent_id' => 'Институты',
                'sort_order' => 2,
                'enabled' => 1
            ],
            [
                'name' => 'Институт сервиса и отраслевого управления',
                'slug' => 'institut-servisa-i-otraslevogo-upravleniia',
                'slug_alternative' => null,
                'parent_id' => 'Институты',
                'sort_order' => 3,
                'enabled' => 1
            ],
            [
                'name' => 'Институт транспорта',
                'slug' => 'institut-transporta',
                'slug_alternative' => null,
                'parent_id' => 'Институты',
                'sort_order' => 4,
                'enabled' => 1
            ],
            [
                'name' => 'Институт геологии и нефтегазодобычи',
                'slug' => 'institut-geologii-i-neftegazodobyci',
                'slug_alternative' => null,
                'parent_id' => 'Институты',
                'sort_order' => 5,
                'enabled' => 1
            ],
            [
                'name' => 'Институт промышленных технологий и инжиниринга',
                'slug' => 'institut-promyslennyx-texnologii-i-inziniringa',
                'slug_alternative' => null,
                'parent_id' => 'Институты',
                'sort_order' => 6,
                'enabled' => 1
            ],
            [
                'name' => 'Институт дополнительного и дистанционного образования',
                'slug' => 'institut-dopolnitelnogo-i-distancionnogo-obrazovaniia',
                'slug_alternative' => null,
                'parent_id' => 'Институты',
                'sort_order' => 7,
                'enabled' => 1
            ],
            [
                'name' => 'Высшая инженерная школа EG',
                'slug' => 'vyssaia-inzenernaia-skola-eg',
                'slug_alternative' => null,
                'parent_id' => 'Институты',
                'sort_order' => 8,
                'enabled' => 1
            ],
            [
                'name' => 'Ноябрьский институт нефти и газа — филиал ТИУ в г.Ноябрьск',
                'slug' => 'noiabrskii-institut-nefti-i-gaza-filial-tiu-v-gnoiabrsk',
                'slug_alternative' => null,
                'parent_id' => 'Филиалы',
                'sort_order' => 1,
                'enabled' => 1
            ],
            [
                'name' => 'Тобольский индустриальный институт — филиал ТИУ в г.Тобольск',
                'slug' => 'tobolskii-industrialnyi-institut-filial-tiu-v-gtobolsk',
                'slug_alternative' => null,
                'parent_id' => 'Филиалы',
                'sort_order' => 2,
                'enabled' => 1
            ],
            [
                'name' => 'Сургутский институт нефти и газа — филиал ТИУ в г.Сургут',
                'slug' => 'surgutskii-institut-nefti-i-gaza-filial-tiu-v-gsurgut',
                'slug_alternative' => null,
                'parent_id' => 'Филиалы',
                'sort_order' => 3,
                'enabled' => 1
            ],
            [
                'name' => 'Филиал ТИУ в г.Нижневартовск',
                'slug' => 'filial-tiu-v-gniznevartovsk',
                'slug_alternative' => null,
                'parent_id' => 'Филиалы',
                'sort_order' => 4,
                'enabled' => 1
            ],
            [
                'name' => 'Студенческий городок',
                'slug' => 'studenceskii-gorodok',
                'slug_alternative' => null,
                'parent_id' => 'Внеучебная деятельность',
                'sort_order' => 1,
                'enabled' => 1
            ],
            [
                'name' => 'Социально-психологическое сопровождение',
                'slug' => 'socialno-psixologiceskoe-soprovozdenie',
                'slug_alternative' => null,
                'parent_id' => 'Внеучебная деятельность',
                'sort_order' => 2,
                'enabled' => 1
            ],
            [
                'name' => 'Молодежный бизнес-инкубатор',
                'slug' => 'molodeznyi-biznes-inkubator',
                'slug_alternative' => null,
                'parent_id' => 'Внеучебная деятельность',
                'sort_order' => 3,
                'enabled' => 1
            ]
        ];

        foreach ($items as $item) {
            if($item['parent_id']) {
                $findCategory = Category::where('name', '=', $item['parent_id'])->first();
                $item['parent_id'] = $findCategory->id ?? null;
            }

            if(!Category::withTrashed()
                ->where('slug', '=', $item['slug'])
                ->first()
            ) {
                $category = new Category([
                    'name' => $item['name'],
                    'slug' => $item['slug'],
                    'slug_alternative' => $item['slug_alternative'],
                    'parent_id' => $item['parent_id'],
                    'sort_order' => $item['sort_order'],
                    'enabled' => $item['enabled']
                ]);

                $category->save();
            }
        }
     }
}
