create database libreria;
CREATE TABLE libreria.libri (
    id INT AUTO_INCREMENT PRIMARY KEY,
    categoria VARCHAR(50),
    titolo VARCHAR(255),
    autore VARCHAR(255),
    prezzo DECIMAL(10,2), -- Specifica precisione e scala
    immagine VARCHAR(255),
    descrizione TEXT
);
drop table libreria.libri ;
INSERT INTO libreria.libri (categoria, titolo, autore, prezzo, immagine, descrizione) VALUES
('FANTASY', 'MISTBORN', 'Brandon Sanderson', 17.10, '../../altre_pagine/immaginiLibri/libriFantasy/MISTBORN.jpg', 
'Mistborn: L\'Ultimo Impero di Brandon Sanderson è un capolavoro del fantasy che mescola magia, rivoluzione e mistero. In un mondo oppresso da un tiranno immortale, una giovane ladruncola scopre di possedere incredibili poteri e si unisce a un gruppo di ribelli con un piano audace: rovesciare l\'impero. Un\'epica storia di speranza, strategia e sacrificio.');
INSERT INTO libreria.libri (categoria, titolo, autore, prezzo, immagine, descrizione) VALUES
('FANTASY', 'GAME OF GODS', 'Rachel J. Bonner', 17.00, '../../altre_pagine/immaginiLibri/libriFantasy/gameofGods.jpeg', 
'Game of Gods di Rachel J. Bonner è un romanzo fantasy intriso di mitologia e battaglie divine. Mentre il confine tra umani e dèi si assottiglia, un giovane eroe scopre di essere la chiave di una profezia che potrebbe decidere il destino del mondo. Tra intrighi, magia e rivelazioni sconvolgenti, il suo viaggio sarà pieno di pericoli e scelte difficili.');
INSERT INTO libreria.libri (categoria, titolo, autore, prezzo, immagine, descrizione) VALUES
('FANTASY', 'A DARK SHADE OF MAGIC', 'V.E. SCHWAB', 12.26, '../../altre_pagine/immaginiLibri/libriFantasy/adsom.jpeg', 
'A Dark Shade of Magic di V.E. Schwab è una storia affascinante su mondi paralleli, magia proibita e avventure straordinarie. Kell, uno degli ultimi maghi in grado di viaggiare tra le diverse Londra, si ritrova coinvolto in una pericolosa cospirazione quando un oggetto proibito cade nelle sue mani. Un viaggio tra regni oscuri, tradimenti e destini intrecciati.');
INSERT INTO libreria.libri (categoria, titolo, autore, prezzo, immagine, descrizione) VALUES
('FANTASY', 'The Priory of the Orange Tree', 'Samantha Shannon', 14.72, '../../altre_pagine/immaginiLibri/libriFantasy/TPOT.jpg', 
'The Priory of the Orange Tree di Samantha Shannon è un fantasy epico che racconta la lotta tra il bene e il male in un mondo minacciato dal ritorno di un antico drago. Con una regina senza eredi e un ordine segreto di guerriere, la storia intreccia magia, politica e battaglie spettacolari in una narrazione mozzafiato.');
INSERT INTO libreria.libri (categoria, titolo, autore, prezzo, immagine, descrizione) VALUES
('AVVENTURA', 'Jurassic Park', 'Michael Crichton', 14.90, '../../altre_pagine/immaginiLibri/libriAvventura/jurassicPark.jpeg', 
'Jurassic Park di Michael Crichton è un thriller di avventura che esplora le conseguenze dell’ambizione scientifica. Un esperimento genetico porta alla creazione di un parco con dinosauri vivi, ma quando il sistema di sicurezza fallisce, il sogno si trasforma in un incubo. Un classico ricco di suspense e azione.');
INSERT INTO libreria.libri (categoria, titolo, autore, prezzo, immagine, descrizione) VALUES
('AVVENTURA', 'Ventimila Leghe Sotto i Mari', 'Jules Verne', 13.99, '../../altre_pagine/immaginiLibri/libriAvventura/ventimilaLeghe.jpeg', 
'Ventimila leghe sotto i mari di Jules Verne è una straordinaria avventura sottomarina. Il professor Aronnax, il suo fedele servitore e un fiociniere vengono catturati dal misterioso Capitano Nemo a bordo del Nautilus. Tra creature marine, battaglie contro giganti degli abissi e segreti nascosti, il viaggio nei mari del mondo sarà indimenticabile.');
INSERT INTO libreria.libri (categoria, titolo, autore, prezzo, immagine, descrizione) VALUES
('AVVENTURA', 'Il codice Da Vinci', 'Dan Brown', 6.20, '../../altre_pagine/immaginiLibri/libriAvventura/codiceDaVinci.jpeg', 
'Il codice Da Vinci di Dan Brown è un thriller mozzafiato che unisce arte, religione e cospirazioni. Dopo un omicidio al Louvre, il professore Robert Langdon e la crittologa Sophie Neveu seguono una serie di indizi nascosti tra i capolavori di Leonardo da Vinci, svelando un mistero che potrebbe cambiare la storia dell’umanità.');
INSERT INTO libreria.libri (categoria, titolo, autore, prezzo, immagine, descrizione) VALUES
('AVVENTURA', 'Hunger Games', 'Suzanne Collins', 13.30, '../../altre_pagine/immaginiLibri/libriAvventura/HG.jpeg', 
'Hunger Games di Suzanne Collins è un romanzo distopico in cui il regime totalitario di Panem obbliga i giovani tributi a combattere in un’arena fino alla morte. Katniss Everdeen si offre volontaria per salvare sua sorella e si trova a sfidare non solo avversari letali, ma anche il sistema oppressivo che governa la sua nazione.');
INSERT INTO libreria.libri (categoria, titolo, autore, prezzo, immagine, descrizione) VALUES
('SPORT', 'È molto semplice', 'Massimiliano Allegri', 18.90, '../../altre_pagine/immaginiLibri/LibriSportivi/èmoltosemplice.jpeg', 
'È molto semplice di Massimiliano Allegri è un libro che raccoglie aneddoti e riflessioni dell’allenatore di calcio sulla gestione di una squadra, la leadership e la mentalità vincente. Con uno stile diretto e pragmatico, l’autore offre consigli applicabili non solo nello sport, ma anche nella vita.');
INSERT INTO libreria.libri (categoria, titolo, autore, prezzo, immagine, descrizione) VALUES
('SPORT', 'The Mamba Mentality', 'Kobe Bryant', 23.75, '../../altre_pagine/immaginiLibri/LibriSportivi/mambamentality.jpeg', 
'The Mamba Mentality di Kobe Bryant è un\'opera ispirazionale che rivela il mindset del leggendario campione NBA. Attraverso fotografie e riflessioni personali, Bryant racconta il suo approccio ossessivo al miglioramento, alla disciplina e alla vittoria, offrendo lezioni di vita e di sport a chiunque voglia raggiungere il massimo delle proprie capacità.');
INSERT INTO libreria.libri (categoria, titolo, autore, prezzo, immagine, descrizione) VALUES
('SPORT', 'Can\'t Hurt Me', 'David Goggins', 23.99, '../../altre_pagine/immaginiLibri/LibriSportivi/canthurtme.jpeg', 
'Can\'t Hurt Me di David Goggins è un libro motivazionale che narra la straordinaria trasformazione dell\'autore da ragazzo insicuro e in sovrappeso a Navy SEAL e ultra-maratoneta. Attraverso sfide estreme e sofferenze inimmaginabili, Goggins dimostra che la forza mentale può abbattere qualsiasi limite.');
INSERT INTO libreria.libri (categoria, titolo, autore, prezzo, immagine, descrizione) VALUES
('SPORT', 'CR7 La Biografia', 'Guillem Balague', 4.75, '../../altre_pagine/immaginiLibri/LibriSportivi/cr7biografia.jpeg', 
'CR7 La Biografia di Guillem Balague esplora la carriera e la vita personale di Cristiano Ronaldo, svelando il percorso che lo ha portato a diventare uno dei calciatori più influenti della storia. Tra sacrifici, successi e rivalità, il libro offre uno sguardo approfondito sulla mentalità vincente di CR7.');


select * from libreria.libri l where  l.categoria = 'FANTASY';

