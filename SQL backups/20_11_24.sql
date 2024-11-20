-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Gegenereerd op: 20 nov 2024 om 11:57
-- Serverversie: 10.4.28-MariaDB
-- PHP-versie: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `Onlinestore`
--

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(300) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(1, 'Telefoonhoesjes'),
(2, 'Telefoonaccessoires'),
(3, 'Tassen'),
(4, 'Airpods accessoires');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `product_name` varchar(100) NOT NULL,
  `discription` text NOT NULL,
  `unit_price` decimal(11,2) NOT NULL,
  `img` text DEFAULT NULL,
  `stock` int(3) DEFAULT NULL,
  `category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `products`
--

INSERT INTO `products` (`id`, `product_name`, `discription`, `unit_price`, `img`, `stock`, `category_id`) VALUES
(1, 'Clear Case', '<p>Laat onze transparante hoesjes opvallen. Ze zijn de perfecte keuze wanneer je je telefoon wilt beschermen en tegelijkertijd een subtiel vleugje kleur of patroon wilt toevoegen, zonder te opvallend te zijn. </p>\n<h3>Voeg een transparante MagSafe telefoonhoes toe voor extra bescherming</h3>\n<p> *Beschermend ontwerp met verhoogde randen ter bescherming van camera en scherm </p>\n<p>*Gecertificeerde valtest van 3 meter (9,84 voet)</p><p>*Compatibel met de draadloze opladers en MagSafe van IDEAL</p><p>Deze telefoonhoes is onafhankelijk getest door een internationaal geaccrediteerd laboratorium van derden en getest op een val van 3 meter (9,84 voet). Houd er rekening mee dat de valtest alleen betrekking heeft op de telefoonhoes en dat IDEAL OF SWEDEN niet verantwoordelijk is voor eventuele schade aan je telefoon.</p>\n<h3> MATERIAAL </h3>\n<p>Gerecycled polycarbonaat en gerecycled thermoplastisch polyurethaan</p><p>Onze heldere hoesjes voor de iPhone 13, iPhone 14 en iPhone 15 zijn ontworpen als een multi-SKU-product. Dit betekent dat één hoesje voor alle drie de modellen past — iPhone 13, iPhone 14 en iPhone 15</p>', 34.99, 'img/clearcase.webp', 5, 1),
(2, 'Black powerbank', '<p>Laad op met stijl, waar je ook bent. Deze draadloze MagSafe-powerbank heeft een strak en tijdloos ontwerp voor dagelijks gebruik. Het maakt het mogelijk om twee apparaten tegelijkertijd op te laden en biedt tot 15W draadloos opladen, en tot 20W met USB C-kabel. Wanneer deze is verbonden met MagSafe, wordt de powerbank toegevoegd aan de widgets op je telefoon, zodat je kunt zien hoeveel batterij er nog over is. Gemaakt van diervrije materialen. Compatibel met MagSafe.</p> <h3>ONTWERP & FUNCTIE</h3><p>Afmetingen; hoogte: 9,6 cm, breedte: 6,5 cm, diepte: 1,45 cm</p><p>5000 mAh batterij</p><p>Laad tot 2 apparaten tegelijkertijd op</p><p>USB C: Ingang; DC5V/3A,9V/2.22A,12V/1.66A Uitgang; max 20W</p>\r\n<p>Draadloos opladen: Uitgang; max 15W</p><h3>MATERIALEN</h3><p>Samenstelling; buitenste schil: ABS-plastic</p><h3>COMPATIBILITEIT</h3><p>Compatibel met Magsafe</p><h3>INBEGREPEN</h3><p>30 cm USB C naar USB C-kabel</p><p>Gebruiksaanwijzing is inbegrepen in de verpakking</p><p>Wandadapter is niet inbegrepen</p>', 59.99, 'img/blackpowerbank.webp', 3, 2),
(3, 'Privacy glass', '<p>Deze privacy-schermbeschermer biedt 3D volledige dekking rond de randen van uw telefoon en 9H hardheid om maximale bescherming te garanderen. Met deze privacyfunctie kunt u uw informatie verborgen houden, zodat anderen het niet kunnen zien. Het is uitgerust met een ultraheldere hydrofobe en oleofobe beschermende coating om vingerafdrukken en vlekken te voorkomen. Gemaakt van Asahi gehard glas dat 0,33 mm dik is.</p><h3>ONTWERP & FUNCTIE</h3><p>Dunne, volledig dekkende ontwerp</p><p>Tweerichtingsverticale privacybescherming</p><p>Dikte: 0,33 mm</p><p>9H hardheidsbehandeling op het oppervlak</p><p>Beschermt het scherm tegen krassen en barsten</p><h3>MATERIALEN</h3><p>3D gebogen Japans Asahi gehard glas</p><h3>INCLUSIEF</h3><p>Bevat een schermbeschermer en reinigingsset, inclusief een microvezel reinigingsdoek, nat reinigingsdoekje, droog reinigingsdoekje en stofabsorber. Bevat een installatiegereedschap dat u begeleidt bij het installeren van het glas op uw telefoon<p>', 29.99, 'img/privacyscreenprotector.webp', 5, 2),
(4, 'Waterproof Pouch Black', '<p>Deze waterbestendige tas biedt handige en veelzijdige bescherming voor je telefoon terwijl deze volledige functionaliteit onder water mogelijk maakt tot 1m voor 30 minuten. De verstelbare riem zorgt voor veilig dragen en hij is gemaakt van milieuvriendelijke materialen. Om vochtschade te voorkomen, zorg ervoor dat je de telefoon na gebruik in water verwijdert en droogt, en het gebruik van een vochtopnemer (\"silica bag\") voor extra bescherming kan de levensduur van je apparaat garanderen. Kortom, deze tas combineert praktisch gebruik en duurzaamheid om je overal verbonden te houden.</p><h3>ONTWERP & FUNCTIE</h3><p>Afmetingen;</p><p>Tas: hoogte; 22 cm, breedte: 12 cm, diepte: 1,5 cm</p><p>Riem: 61 cm op zijn langst</p><h3>MATERIALEN</h3><p>Samenstelling; 70 % gerecycled TPU, 30 % TPU, Clip; 100 % ABS,\r\nRiem; 100 % PET</p><h3>COMPATIBILITEIT</h3><p>Deze waterdichte tas past bij de meeste telefoonformaten</p>', 29.99, 'img/waterproofpouchblack.webp', 8, 3),
(5, 'Azura Marble Case', '<p>Geef je telefoon een persoonlijk tintje door een van onze unieke ontwerpen te kiezen. Deze strakke maar beschermende hoesjes hebben verhoogde randen rond de camera en het scherm, en een gladde microvezelvoering om krassen te voorkomen. Onderdeel van onze magnetisch compatibele productlijn en gemaakt van dierproefvrije materialen.</p><h3>ONTWERP & FUNCTIE</h3><p>Strak en beschermend ontwerp gemaakt van sterk en duurzaam polycarbonaat</p><p>Verhoogde randen om de camera en het scherm te beschermen</p><p>Microfiber voering om krassen te voorkomen</p><h3>MATERIALEN</h3><p>Samenstelling; 100% polycarbonaat</p><p>Voering: 100% polyester</p><p>Metalen hardware: 100% zinklegering</p><h3>COMPATIBILITEIT</h3><p>Compatibel met IDEAL\'s Draadloze Opladers</p><p>Accessories of Attraction: onderdeel van ons magnetisch compatibel assortiment.</p>', 34.99, 'img/azuramarblecase.png', 3, 1),
(6, 'Eagle Black Laptoptas', '<p>Verfris je laptop met een elegante en stijlvolle tas die vorm en functie combineert. Het beschermt niet alleen tegen krassen en stoten, maar voegt ook een persoonlijke touch toe aan je apparatuur. Zorgvuldig vervaardigd, maakt deze tas elke reis en elk cafébezoek een kans om je unieke stijl te laten zien, terwijl je apparaat veilig en stijlvol blijft.</p>\r\n<p>Unity messenger-laptoptas, ontworpen voor de meeste laptops tot 16\"\r\n*Beschermend design\r\n*Gemaakt van gerecyclede en diervrije materialen\r\n*Slim zakontwerp</p><h3>MATERIALEN</h3><p>Samenstelling: Gerecycled polyurethaan, polyurethaan\r\nHardware: Zinklegering, ijzer\r\nVoering: Gerecycled polyester</p><h3>AFMETINGEN</h3><p>Tas: Hoogte: 28 cm, breedte: 39 cm, diepte: 4 cm</p>\r\n<p>Band: Lengte: 46-188 cm</p><ul><li>Buitenkant afmetingen: Breedte 39 cm x Lengte 28 cm x Diepte 4 cm</li><li>Binnenkant afmetingen: Breedte 36,5 cm x Lengte 27 cm x Diepte 2,5 cm</li>\r\n<li>Twee voorvakjes met rits.</li><li>Dit product is vervaardigd uit ethische en dierproefvrije materialen</li><li>Bevat voorvak dat geschikt is voor alle telefoonmodellen.</li><li>Omvat een verstelbare riem die kan worden losgemaakt.</li><li>De riem meet 146 tot 188cm.</li><li>Inclusief een sleutelhanger aan de voorkant om je sleutels aan te bevestigen.</li><li>Donker zilverkleurige hardware details.</li>', 149.99, 'img/eagleblacklaptoptas.webp', 2, 3),
(7, 'Clover Gold Charm', 'Deze telefoonbedel is ideaal om je hoesje te versieren en je hele look te verbeteren. Het is gemakkelijk te gebruiken en kan aan de meeste telefoonhoesjes worden bevestigd. De bevestigingspatch is inbegrepen.\r\nONTWERP & FUNCTIE\r\nAfmetingen: lengte exclusief haak: 1,4 cm breedte: 2,35 cm\r\nBevestigingspatch: lengte: 6 cm, hoogte: 4 cm\r\nTotaalgewicht: 5,5 gram\r\n\r\nMATERIALEN\r\nSamenstelling: 100% zinklegering, 100% ijzer\r\nBevestigingspatch: 100% TPU\r\n\r\nCOMPATIBILITEIT\r\nDeze charm past op de meeste telefoonhoesjes, niet compatibel met iPhone-model X, XS, X Max (gecombineerd met IDEAL OF SWEDEN CASES 11 Pro en 11Pro Max). We raden niet aan om dit product te combineren met onze transparante of siliconen hoezen als je een van de volgende telefoonmodellen hebt: iPhone 11/11 Pro/XR, XS, XS Max. Volg onze gids over hoe je het moet gebruiken.\r\n\r\nINBEGREPEN\r\nEen bevestigingspatch om aan je telefoonhoesje te koppelen (telefoonhoesjes worden apart verkocht)\r\nLet op dat de bevestigingspatch/de charm moet worden bevestigd aan het telefoonhoesje volgens onze instructies in de handleiding. Zo niet, dan is IDEAL OF SWEDEN niet verantwoordelijk voor eventuele schade of het ontbreken van functionaliteit van het product of je telefoon.', 14.99, 'img/charmclovergold.avif', 6, 2),
(8, 'Orange Spritz Airpods Case', 'Maak uw AirPods persoonlijker met deze transparante hoes. Het wordt geleverd met een functionele karabijnhaak en heeft een beschermend ontwerp om te voorkomen dat uw AirPods bekrast raken. Deze Airpod-hoes is gemaakt van diervrije en gerecyclede materialen.\r\nONTWERP & FUNCTIE\r\nFunctionele haak\r\nBeschermend ontwerp\r\nQi-compatibel\r\n\r\nMATERIAAL\r\nSamenstelling: 100% TPU, Haak; 100% Zinklegering\r\n\r\nCOMPATIBILITEIT\r\nCompatibel met IDEAL\'s draadloze opladers\r\nCompatibel met Airpod PRO 1&2', 27.99, 'img/orangespritzairpodscase.png', 7, 4),
(11, 'Warm Beige Croco', 'Overzicht Een klassieker gaat nooit uit de mode. Een telefoonhoesje van vegan leer biedt betrouwbare bescherming voor je telefoon, terwijl het een elegant en tijdloos uiterlijk behoudt. Het duurzame materiaal zorgt voor langdurig gebruik en combineert stijl en functionaliteit.  Bescherm je telefoon met een MagSafe-hoesje van vegan leer Gecertificeerde valtest vanaf 4 meter (13,12 voet) Beschermend ontwerp met opstaande randen ter bescherming van camera en scherm Compatibel met de draadloze opladers van IDEAL OF SWEDEN en MagSafe Dit telefoonhoesje is onafhankelijk getest door een internationaal geaccrediteerd derdepartijlaboratorium en heeft een valtest doorstaan vanaf 4 meter (13,12 voet). Houd er rekening mee dat de valtest alleen betrekking heeft op de telefoonhoes en dat IDEAL OF SWEDEN niet verantwoordelijk is voor eventuele schade aan je telefoon. We raden het gebruik van onze stickers of kaartjeshouders met lijm op dit hoesje af.  MATERIAAL Polyurethaan, Gerecycled polyurethaan, Gerecycled thermoplastisch polyurethaan, Gerecycled polycarbonaat', 44.99, 'img/warmbeigecroco.webp', 5, 1),
(12, 'Crossbody Phone Bag Black', 'Deze universele mobiele telefoontas met rits is ideaal om je mobiel en kleinere accessoires mee te nemen. Het verhoogt je look en voegt een functionele touch toe aan je dagelijkse benodigdheden. De tas is waterdicht tegen motregen/lichte regen. Gemaakt van gerecyclede en dierproefvrije materialen. ONTWERP & FUNCTIE Afmetingen; Tas: hoogte; 18 cm, breedte: 11,5 cm, diepte: 5 cm Riem: lengte: 140 cm breedte: 2,6 cm  MATERIAAL Samenstelling; 100% gerecycled polyamide, 100% polyurethaan, Voering; 100% gerecycled polyester, Haak; 100% zinklegering, Rits; 100% koper Riem; 3% polyester, 97% gerecycled polyester  COMPATIBILITEIT Deze universele mobiele telefoontas past op de meeste mobiele telefoonformaten', 59.99, 'img/crossbodyphonebagblack.webp', 4, 3),
(13, 'Mirror Airpods Case', 'Je perfecte partner onderweg, dit metallic spiegel AirPods-hoesje heeft een reflecterende oppervlakte die zowel stijl als praktische functionaliteit aan je dagelijks leven toevoegt.  Voeg een beschermlaag toe aan je AirPods met onze Spiegel AirPods-hoesjes Functionele haak Beschermend ontwerp Qi-compatibel Door het spiegeleffect kunnen krassen zichtbaarder zijn. Houd er rekening mee dat AirPods niet zijn inbegrepen.  MATERIAAL Gerecycled TPU, Haak: Zinklegering  COMPATIBILITEIT Compatibel met Qi en de draadloze opladers van IDEAL OF SWEDEN Het hoesje past op AirPods PRO 1 & 2', 29.99, 'img/mirrorairpodcase.webp', 5, 4),
(14, 'Tinded Black Airpods Case', 'Overzicht Maak uw AirPods persoonlijker met deze transparante hoes. Het wordt geleverd met een functionele karabijnhaak en heeft een beschermend ontwerp om te voorkomen dat uw AirPods bekrast raken. Deze Airpod-hoes is gemaakt van diervrije en gerecyclede materialen. ONTWERP & FUNCTIE Functionele haak Beschermend ontwerp Qi-compatibel  MATERIAAL Samenstelling: 100% TPU, Haak; 100% Zinklegering  COMPATIBILITEIT Compatibel met IDEAL\'s draadloze opladers Compatibel met Airpod PRO 1&2', 27.99, 'img/tindedblackairpodscase.png', 5, 4),
(15, 'Midnight Blue case', '<p>Zeg vaarwel tegen gladde telefoonhoesjes met onze siliconen hoesjes, met een licht geborsteld oppervlak en een veilige grip. De innovatieve textuur zorgt voor een stevige grip, waardoor het risico dat je je telefoon laat vallen, vermindert. Bovendien geven de speelse kleuren je apparaat een persoonlijke touch terwijl het veilig en stijlvol blijft.</p><h3>Siliconen hoesje met licht geborsteld oppervlak</h3><p>*Val getest vanaf 2m / 6.56voet.</p><p>*Met verhoogde randen ter bescherming van camera en scherm</p><p>*Beschermende voering van microvezel</p><p>Dit product is onafhankelijk getest op vallen door een internationaal geaccrediteerd laboratorium. Houd er rekening mee dat de valtest alleen het telefoonhoesje betreft en dat IDEAL OF SWEDEN niet verantwoordelijk is voor eventuele schade aan je telefoon.</p><h3>MATERIALEN</h3><p>Gerecycled polycarbonaat, vloeibaar siliconen</p><p>Voering: gerecycled polyester & polyester</p><h3>ONTWERP & FUNCTIE</h3><ul><li>Beschermend ontwerp met verhoogde randen om de camera en het scherm te beschermen</li><li>Microfiber voering om krassen te voorkomen</li><li>Volledige bescherming op maat</li><li>Antistof- en vingerafdrukcoating</li><li>Deze product is gedropt-getest van 1,52 meter/5 feet.</li><li>Soft-touch afwerking en gripvriendelijk oppervlak</li></ul><h3>MATERIALEN</h3><p>Samenstelling: 100% gerecycled polycarbonaat; 100% vloeibare siliconen; Voering: 51% gerecycled polyester</p><h3>COMPATIBILITEIT</h3><p>Compatibel met IDEAL\'s Draadloze Opladers</p><p>Dit product is onafhankelijk getest door een internationa erkend laboratorium en is gedpt uit 5 voet (1,52 meter).</p><p>Let op, de valtest dekt alleen de telefoonhoes en IDEAL OF SWEDEN is niet verantwoordelijk voor eventuele schade aan uw telefoon.</p>', 24.99, 'Img/midnightbluecase.webp', 4, 1),
(16, 'Petite Floral AirPods Case', '<p>Maak uw AirPods persoonlijker met deze transparante hoes. Het wordt geleverd met een functionele karabijnhaak en heeft een beschermend ontwerp om te voorkomen dat uw AirPods bekrast raken. Deze Airpod-hoes is gemaakt van diervrije en gerecyclede materialen.</p><h3>ONTWERP & FUNCTIE</h3><p>Functionele haak</p><p>Beschermend</p><p>ontwerp</p><p>Qi-compatibel</p><h3>MATERIAAL</h3><p>Samenstelling: 100% TPU, Haak; 100% Zinklegering</p><h3>COMPATIBILITEIT</h3><p>Compatibel met IDEAL\'s draadloze opladers</p><p>Compatibel met Airpod PRO 1&2</p><br><p>Airpods zijn NIET inbegrepen</p>', 27.99, 'img/petitefloralairpodcase.png', 4, 4);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `firstname` varchar(300) NOT NULL,
  `lastname` varchar(300) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `firstname`, `lastname`) VALUES
(3, 'fien@shop.com', '$2y$12$HEJlvFRWl/Gss8DF3MPYTuuVQOXIe8ZI1Oy4D74eNAj7thw8aiaeC', 'Fien', 'Wouters'),
(4, 'test@test.com', '$2y$12$r/pCDw69OhexRY/PE9ZHXuCTJEAPSKAlN6dvyfbG6WujwyG9R9vxi', 'test', 'tester'),
(7, 'Annedesmet@outlook.com', '$2y$12$lqAw.s4HeCp0yvIiQl5FTO3i.39UXj7hTgNdN4LK7NEv3J2aE.lsm', 'Anne', 'De Smet'),
(9, 'admin@admin.com', '$2y$12$1JGwJUQdI5O19XJzNWumQuRaSr6kmpb8QkivppGSsnHVCH2D/XBg.', 'Admin', 'Admin'),
(10, 'user@user.com', '$2y$12$ODVhFPWwVQWmW.tm6vzSy.9j4R3lSoiTVpOl2ytFtpaAppE41qe7W', 'User', 'User');

--
-- Indexen voor geëxporteerde tabellen
--

--
-- Indexen voor tabel `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT voor geëxporteerde tabellen
--

--
-- AUTO_INCREMENT voor een tabel `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT voor een tabel `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT voor een tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
