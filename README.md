# dgdat to xlsx конвертер

Конвертирует базы ДубльГис [https://2gis.ru/](https://2gis.ru/) в файлы формата Microsoft Excel.  
Все данные берутся только из файлов формата dgdat, обращение к интернету не требуется.  

## Установка

```
git clone https://github.com/mbry/DgdatToXlsx
composer update
```

## Сконвертировать все *.dgdat файлы в папке download в файлы формата Ms Excel

```
php convert.php
```

## Следующие поля присутствуют в результирующем Excel файле

* ID
* Название организации
* Населенный пункт
* Раздел
* Подраздел
* Рубрика
* Телефоны
* Факсы
* Email
* Сайт
* Адрес
* Почтовый индекс
* Типы платежей
* Время работы
* Собственное название строения
* Назначение строения
* Vkontakte
* Facebook
* Skype
* Twitter
* Instagram
* ICQ
* Jabber   

## Проверена работоспособность на следующих файлах (на 08.09.2016)

Abakan-50.0.0.dgdat; Almaty-52.0.0.dgdat; Almetevsk-24.0.0.dgdat; Arkhangelsk-60.0.0.dgdat; Armawir-29.0.0.dgdat; Astana-13.0.0.dgdat; Astrakhan-129.0.0.dgdat; Barnaul-138.0.0.dgdat; Belgorod-62.0.0.dgdat; Bishkek-10.0.0.dgdat; Biysk-94.0.0.dgdat; Blagoveshensk-59.0.0.dgdat; Bratsk-59.0.0.dgdat; Bryansk-54.0.0.dgdat; Cheboksary-58.0.0.dgdat; Chelyabinsk-108.0.0.dgdat; Chita-52.0.0.dgdat; Dnepropetrovsk-28.0.0.dgdat; Donetsk-45.0.0.dgdat; Ekaterinburg-128.0.0.dgdat; Gornoaltaysk-76.0.0.dgdat; Irkutsk-124.0.0.dgdat; Ivanovo-52.0.0.dgdat; Izhevsk-65.0.0.dgdat; K_uralskiy-22.0.0.dgdat; Kaliningrad-66.0.0.dgdat; Kaluga-54.0.0.dgdat; Karaganda-41.0.0.dgdat; Kazan-93.0.0.dgdat; Kemerovo-136.0.0.dgdat; Khabarovsk-69.0.0.dgdat; Kharkov-16.0.0.dgdat; Kiev-6.0.0.dgdat; Kirov-55.0.0.dgdat; Komsomolsk-37.0.0.dgdat; Kostroma-70.0.0.dgdat; Krasnodar-79.0.0.dgdat; Krasnoyarsk-132.0.0.dgdat; Kurgan-125.0.0.dgdat; Kursk-51.0.0.dgdat; Lenkuz-43.0.0.dgdat; Lipetsk-57.0.0.dgdat; Magnitogorsk-76.0.0.dgdat; Makhachkala-5.0.0.dgdat; Miass-42.0.0.dgdat; Minvody-40.0.0.dgdat; Moscow-64.0.0.dgdat; Murmansk-25.0.0.dgdat; N_Novgorod-95.0.0.dgdat; Nabchelny-74.0.0.dgdat; Nahodka-42.0.0.dgdat; Nizhnevartovsk-123.0.0.dgdat; Norilsk-48.0.0.dgdat; Novokuznetsk-132.0.0.dgdat; Novorossiysk-51.0.0.dgdat; Novosibirsk-215.0.0.dgdat; Noyabrsk-33.0.0.dgdat; Ntagil-62.0.0.dgdat; Odessa-114.0.0.dgdat; Omsk-144.0.1.dgdat; Orel-46.0.0.dgdat; Orenburg-61.0.0.dgdat; P_kamchatskiy-26.0.0.dgdat; Pavlodar-11.0.0.dgdat; Penza-64.0.0.dgdat; Perm-104.0.0.dgdat; Petrozavodsk-46.0.0.dgdat; Pskov-40.0.0.dgdat; Rostov-77.0.0.dgdat; Ryazan-64.0.0.dgdat; Samara-97.0.0.dgdat; Saransk-43.0.0.dgdat; Saratov-64.0.0.dgdat; Smolensk-50.0.0.dgdat; Sochi-73.0.0.dgdat; Spb-66.0.5.dgdat; Staroskol-54.0.0.dgdat; Stavropol-56.0.0.dgdat; Sterlitamak-57.0.0.dgdat; Surgut-66.0.0.dgdat; Syktyvkar-53.0.0.dgdat; Tambov-43.0.0.dgdat; Tobolsk-38.0.0.dgdat; Togliatti-97.0.0.dgdat; Tomsk-140.0.0.dgdat; Tula-69.0.0.dgdat; Tver-61.0.0.dgdat; Tyumen-117.0.0.dgdat; Ufa-101.0.0.dgdat; Ulanude-67.0.0.dgdat; Ulyanovsk-57.0.0.dgdat; Ussuriysk-42.0.0.dgdat; Ustkam-39.0.0.dgdat; V_Novgorod-49.0.0.dgdat; Vladimir-54.0.0.dgdat; Vladivostok-77.0.0.dgdat; Volgograd-71.0.0.dgdat; Vologda-49.0.0.dgdat; Voronezh-73.0.0.dgdat; Yakutsk-60.0.0.dgdat; Yaroslavl-72.0.0.dgdat; Yoshkarola-52.0.0.dgdat; Yuzhnosakhalinsk-42.0.0.dgdat
