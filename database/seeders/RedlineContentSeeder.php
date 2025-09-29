<?php

namespace Database\Seeders;

use App\Models\Game;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class RedlineContentSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        $authorProfiles = [
            ['email' => 'sofia.independencia@redline.test', 'name' => 'Sofía Méndez', 'role' => 'docente'],
            ['email' => 'ricardo.sandino@redline.test', 'name' => 'Ricardo Sandino', 'role' => 'docente'],
            ['email' => 'camila.cultura@redline.test', 'name' => 'Camila Rivas', 'role' => 'docente'],
            ['email' => 'ernesto.personajes@redline.test', 'name' => 'Ernesto Talavera', 'role' => 'docente'],
            ['email' => 'adriana.guerras@redline.test', 'name' => 'Adriana Cordero', 'role' => 'docente'],
            ['email' => 'valeria.patrimonio@redline.test', 'name' => 'Valeria Sevilla', 'role' => 'docente'],
            ['email' => 'martin.educacion@redline.test', 'name' => 'Martín Alvarado', 'role' => 'docente'],
            ['email' => 'lorena.internacionales@redline.test', 'name' => 'Lorena Gaitán', 'role' => 'docente'],
            ['email' => 'hector.eventos@redline.test', 'name' => 'Héctor Robleto', 'role' => 'docente'],
            ['email' => 'natalia.quiz@redline.test', 'name' => 'Natalia Quiroz', 'role' => 'docente'],
            ['email' => 'javier.library@redline.test', 'name' => 'Javier Molina', 'role' => 'docente'],
            ['email' => 'patricia.archivo@redline.test', 'name' => 'Patricia Centeno', 'role' => 'docente'],
        ];

        $authorIds = collect($authorProfiles)->mapWithKeys(function (array $profile) {
            $user = User::firstOrCreate(
                ['email' => $profile['email']],
                [
                    'name' => $profile['name'],
                    'password' => Hash::make('password'),
                    'role' => $profile['role'],
                ]
            );

            return [$profile['email'] => $user->id];
        });

                $disk = Storage::disk('public');
                $disk->makeDirectory('covers');
                $disk->makeDirectory('library');

                $writePoster = function (string $path, string $headline, string $subline, string $footer, string $primary, string $secondary, string $badge = 'NI') use ($disk) {
                        if ($disk->exists($path)) {
                                return;
                        }

                        $svg = <<<SVG
<?xml version="1.0" encoding="UTF-8"?>
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 720 480">
    <defs>
        <linearGradient id="grad" x1="0" y1="0" x2="1" y2="1">
            <stop offset="0%" stop-color="{$primary}"/>
            <stop offset="100%" stop-color="{$secondary}"/>
        </linearGradient>
    </defs>
    <rect width="720" height="480" fill="url(#grad)"/>
    <g font-family="'Poppins','Inter',sans-serif">
        <text x="48" y="156" font-size="46" font-weight="700" fill="#e2e8f0">{$headline}</text>
        <text x="48" y="212" font-size="22" fill="#cbd5f5">{$subline}</text>
        <text x="48" y="258" font-size="18" fill="#bae6fd">{$footer}</text>
    </g>
    <g transform="translate(560,140)">
        <circle r="84" fill="rgba(15,23,42,0.42)" stroke="rgba(226,232,240,0.55)" stroke-width="2"/>
        <text text-anchor="middle" dominant-baseline="central" font-size="48" font-weight="700" fill="#f8fafc">{$badge}</text>
    </g>
</svg>
SVG;

                        $disk->put($path, $svg);
                };

            $writePoster('covers/historia-precolombina.svg', 'Nicaragua Precolombina', 'Huellas de Acahualinca · Chorotegas · Nicaraos', 'Migraciones ancestrales del istmo', '#0f172a', '#1d4ed8', 'ACAH');
            $writePoster('covers/historia-colonial.svg', 'Colonial y rivalidad urbana', 'Granada · León · León Viejo', 'Fundaciones de 1524 y patrimonio UNESCO', '#111827', '#9333ea', 'COL');
            $writePoster('covers/historia-nacional.svg', 'Siglo XIX y soberanía', 'Independencia · Guerra Nacional · Mosquitia', 'Unión centroamericana y resistencia filibustera', '#1e293b', '#f97316', 'XIX');
            $writePoster('covers/historia-sigloxx.svg', 'Siglo XX y soberanía', 'Sandino · Somoza · Revolución 1979', 'Resistencia nacional y cambios sociales', '#082f49', '#ef4444', 'XX');
            $writePoster('covers/historia-tapiz-cultural.svg', 'Tapiz cultural actual', 'Multiétnica · Gritería · Palo de Mayo', 'Tradiciones vivas de Nicaragua', '#0f172a', '#0ea5e9', 'CULT');
            $writePoster('covers/historia-independencia.svg', 'Acta de Independencia', 'Guatemala · 15 septiembre 1821', 'Archivo General de Centroamérica', '#0f172a', '#1d4ed8', '1821');
                $writePoster('covers/historia-revolucion.svg', 'Revolución Sandinista', 'Frente Sandinista · 1979', 'Archivo General de la Nación', '#1e1b4b', '#be123c', 'FSLN');
                $writePoster('covers/historia-gueguense.svg', 'El Güegüense', 'Diriamba · Teatro mestizo', 'Patrimonio Oral de la UNESCO', '#083344', '#14b8a6', 'CULT');
                $writePoster('covers/historia-san-jacinto.svg', 'Batalla de San Jacinto', 'Hacienda San Jacinto · 1856', 'Ejército patriota nicaragüense', '#111827', '#fb923c', '1856');
                $writePoster('covers/historia-ruben-dario.svg', 'Rubén Darío', 'Metapa · 1867', 'Príncipe de las letras castellanas', '#0f172a', '#7c3aed', 'RD');
                $writePoster('covers/historia-gastronomia.svg', 'Sabores de Cuaresma', 'Recetario tradicional', 'Sopa de queso · Almíbares · Tamugas', '#03111f', '#0f766e', 'SAB');

                $writePoster('covers/noticia-san-jacinto.svg', 'Honores a San Jacinto', 'Tipitapa · 14 de septiembre', 'Desfiles estudiantiles y ofrendas florales', '#1f2937', '#f97316', '169');
                $writePoster('covers/noticia-unesco.svg', 'UNESCO destaca tradiciones', 'Gigantona y Toro Huaco', 'Expresiones declaradas patrimonio cultural', '#0f172a', '#2563eb', 'UNESCO');
                $writePoster('covers/noticia-poesia.svg', 'Festival de Poesía Granada', 'Edición XVIII · 2025', 'Recitales en plazas coloniales', '#1e293b', '#22d3ee', 'FP');
                $writePoster('covers/noticia-museo.svg', 'Sala arqueológica INA', 'Colecciones Ometepe · Chontales', 'Museo Nacional de Nicaragua', '#082f49', '#60a5fa', 'INA');
                $writePoster('covers/noticia-cuaresma.svg', 'Recetas de Cuaresma', 'Sabores tradicionales estudiantiles', 'Investigación culinaria escolar', '#0b1120', '#fbbf24', 'GDA');

                $writePoster('library/acta1821.svg', 'Acta Centroamericana', 'Edición restaurada 1821', 'Documento histórico para descarga escolar', '#1f2937', '#38bdf8', 'DOC');
                $writePoster('library/gueguense.svg', 'Guía teatral Güegüense', 'Transcripción bilingüe', 'Notas críticas y contexto histórico', '#0f172a', '#ec4899', 'PDF');
                $writePoster('library/revolucion-imagenes.svg', 'Fototeca 1979', 'Archivo visual del triunfo sandinista', 'Colección digital de Eduardo Pérez', '#111827', '#facc15', 'IMG');
                $writePoster('library/catedral-leon.svg', 'Catedral de León', 'Patrimonio Mundial 2011', 'Galería de restauración y bóvedas', '#0f172a', '#38bdf8', 'UNESCO');
                $writePoster('library/recetario-cuaresma.svg', 'Recetario de Cuaresma', 'Maíz nuevo · Pescado seco · Dulces', 'Rutas gastronómicas nicaragüenses', '#06283d', '#f97316', 'FOOD');
            $writePoster('library/podcast-segovias.svg', 'Podcast Las Segovias', 'Memorias campesinas y rutas de Sandino', 'Guía de escucha para aulas comunitarias', '#1e1b4b', '#22c55e', 'AUDIO');

        $categories = [
            ['name' => 'Independencia', 'slug' => 'independencia-nicaragua'],
            ['name' => 'Revolución Sandinista', 'slug' => 'revolucion-sandinista'],
            ['name' => 'Cultura y Tradiciones', 'slug' => 'cultura-tradiciones'],
            ['name' => 'Personajes Históricos', 'slug' => 'personajes-historicos'],
            ['name' => 'Guerras y Batallas', 'slug' => 'guerras-batallas'],
            ['name' => 'Patrimonio Nacional', 'slug' => 'patrimonio-nacional'],
            ['name' => 'Educación y Cultura', 'slug' => 'educacion-cultura'],
            ['name' => 'Relaciones Internacionales', 'slug' => 'relaciones-internacionales'],
            ['name' => 'Arte y Literatura', 'slug' => 'arte-literatura'],
            ['name' => 'Gastronomía Tradicional', 'slug' => 'gastronomia-tradicional'],
        ];

        foreach ($categories as $category) {
            DB::table('categories')->updateOrInsert(
                ['slug' => $category['slug']],
                [
                    'name' => $category['name'],
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );
        }

        $categoryIds = DB::table('categories')->pluck('id', 'slug');

        $histories = [
            [
                'title' => 'La Nicaragua Precolombina',
                'slug' => 'nicaragua-precolombina-civilizaciones',
                'excerpt' => 'Huellas de Acahualinca, migraciones chorotegas y la llegada de los Nicaraos moldearon las primeras culturas del istmo.',
                'era' => 'Período precolombino',
                'leading_figure' => 'Cacique Nicarao',
                'content' => <<< 'TEXT'
Las Huellas de Acahualinca, conservadas en Managua, constituyen una de las evidencias más antiguas de presencia humana en Nicaragua. Las pisadas, fosilizadas en ceniza volcánica, se han fechado entre 2,100 y casi 6,000 años de antigüedad según distintos estudios, y narran la historia de familias en movimiento alrededor de una laguna.

Siglos después, el territorio se convirtió en un corredor cultural. Los Chorotegas o Mangues dominaron el Pacífico central con centros urbanos y un alto conocimiento agrícola; mientras que los Nicaraos o Niquiranos, de origen náhuatl, ingresaron desde el norte portando lenguas y cosmovisiones mesoamericanas. Los Maribios habitaron la franja volcánica y dejaron evidencia cerámica, petrograbados y sistemas de intercambio.

El nombre del país combina la figura del cacique Nicarao, líder asentado en Rivas cuando llegaron los españoles, con las voces náhuatl que evocan el agua que rodea al territorio. El lago Cocibolca, con su Isla de Ometepe –dos volcanes emergiendo de un lago dulce–, fue el signo que, según la tradición, confirmó a los Nicaraos que habían encontrado su hogar, cumpliendo la visión religiosa de sus sacerdotes.
TEXT,
                'cover_path' => 'covers/historia-precolombina.svg',
                'category_slug' => 'cultura-tradiciones',
                'author_email' => 'camila.cultura@redline.test',
            ],
            [
                'title' => 'Colonialismo y rivalidad entre ciudades',
                'slug' => 'epoca-colonial-rivalidad-ciudades',
                'excerpt' => 'Granada y León, fundadas en 1524, marcaron el pulso político de la colonia y legaron patrimonio único como León Viejo y el Güegüense.',
                'era' => 'Época colonial',
                'leading_figure' => 'Francisco Hernández de Córdoba',
                'content' => <<< 'TEXT'
Cristóbal Colón bordeó la Costa Caribe en 1502 durante su cuarto viaje, bautizando al cabo Gracias a Dios. La conquista tierra adentro se concretó dos décadas más tarde: Francisco Hernández de Córdoba fundó Granada a orillas del Gran Lago y León cerca del volcán Momotombo en 1524. León Viejo, destruida por erupciones y sismos, permanece como una cápsula arqueológica declarada Patrimonio Mundial.

La pugna entre León (de tinte liberal) y Granada (de tendencias conservadoras) definió la administración colonial y siguió viva tras la independencia. Ambas ciudades disputaban la sede del poder político, las aduanas y las rutas comerciales. El conflicto terminó solamente cuando, en 1858, Managua fue designada capital neutral.

En medio de esa tensión surgió El Güegüense, obra satírica que mezcla náhuatl y castellano para ridiculizar los abusos coloniales. La UNESCO la reconoció en 2005 como Obra Maestra del Patrimonio Oral e Intangible de la Humanidad, símbolo de cómo las comunidades indígenas negociaron, resistieron y se burlaron del orden impuesto.
TEXT,
                'cover_path' => 'covers/historia-colonial.svg',
                'category_slug' => 'patrimonio-nacional',
                'author_email' => 'valeria.patrimonio@redline.test',
            ],
            [
                'title' => 'Siglo XIX: Independencia y soberanía',
                'slug' => 'siglo-xix-intervencion-extranjera',
                'excerpt' => 'De la independencia de 1821 a la derrota del filibustero William Walker y la reincorporación de la Mosquitia.',
                'era' => 'Siglo XIX',
                'leading_figure' => 'José Santos Zelaya',
                'content' => <<< 'TEXT'
Nicaragua proclamó su independencia el 15 de septiembre de 1821 y se integró a la Federación Centroamericana hasta 1838. La joven república afrontó caídos gobiernos provinciales, invasiones externas y el reto de mantener la unidad frente a intereses extranjeros.

Durante la Guerra Nacional (1856-1857), el filibustero estadounidense William Walker aprovechó la rivalidad política para tomar el poder y reinstaurar la esclavitud. La unión de los ejércitos centroamericanos lo derrotó en San Jacinto y Santa Rosa, consolidando un hito de soberanía. A finales de siglo, el presidente José Santos Zelaya reincorporó la Mosquitia o Costa Caribe —bajo protectorado británico— reafirmando la integridad territorial con apoyo diplomático y militar.
TEXT,
                'cover_path' => 'covers/historia-nacional.svg',
                'category_slug' => 'relaciones-internacionales',
                'author_email' => 'sofia.independencia@redline.test',
            ],
            [
                'title' => 'Siglo XX: Lucha por la soberanía',
                'slug' => 'siglo-xx-lucha-soberania',
                'excerpt' => 'Sandino, la dinastía Somoza, el terremoto de 1972 y la Revolución Sandinista marcaron el rumbo del siglo.',
                'era' => 'Siglo XX',
                'leading_figure' => 'Augusto C. Sandino',
                'content' => <<< 'TEXT'
Las potencias vieron en Nicaragua un punto estratégico para un canal interoceánico. A inicios del siglo XX, tropas estadounidenses ocuparon el país (1912-1933). Contra esa presencia emergió Augusto C. Sandino, quien lideró un ejército campesino popular bajo el lema "Patria y Libertad" y forzó la retirada norteamericana.

Sin embargo, la Guardia Nacional dirigida por Anastasio Somoza García asesinó a Sandino en 1934 e instauró una dinastía familiar que gobernó con mano dura más de cuarenta años. El terremoto de Managua de 1972, que devastó la capital, evidenció la corrupción del régimen somocista al gestionar la ayuda internacional. La indignación social impulsó al Frente Sandinista de Liberación Nacional a coordinar la insurrección popular que triunfó el 19 de julio de 1979.

El legado cultural del periodo incluye al poeta Rubén Darío, padre del modernismo y diplomático que internacionalizó la literatura nicaragüense, proyectando la identidad del país más allá de sus fronteras.
TEXT,
                'cover_path' => 'covers/historia-sigloxx.svg',
                'category_slug' => 'revolucion-sandinista',
                'author_email' => 'ricardo.sandino@redline.test',
            ],
            [
                'title' => 'Tapiz cultural de la Nicaragua actual',
                'slug' => 'tapiz-cultural-nicaragua-actual',
                'excerpt' => 'País multiétnico donde conviven la Gritería, el Palo de Mayo y una cocina diversa como el gallo pinto o el vigorón.',
                'era' => 'Nicaragua contemporánea',
                'leading_figure' => 'Diversidad cultural nicaragüense',
                'content' => <<< 'TEXT'
La Constitución reconoce a Nicaragua como nación multiétnica, multilingüe y pluricultural. Esta riqueza se aprecia con claridad en la Costa Caribe, donde los pueblos Miskito, Mayangna, Rama, Garífuna y la población Creole de habla inglesa criolla comparten territorios y saberes.

El calendario festivo une regiones: la Gritería, celebrada cada 7 de diciembre, convierte las calles de León y otras ciudades en altares vivientes en honor a la Purísima Concepción. En el Caribe, el Palo de Mayo anima mayo con ritmos afrocaribeños que celebran la fertilidad y la temporada de lluvias. La gastronomía refuerza esos lazos: el gallo pinto desayuna al país, el vigorón acompaña ferias y parques, mientras que los nacatamales reúnen a la familia los fines de semana.
TEXT,
                'cover_path' => 'covers/historia-tapiz-cultural.svg',
                'category_slug' => 'educacion-cultura',
                'author_email' => 'martin.educacion@redline.test',
            ],
            [
                'title' => 'Acta de Independencia de Centroamérica',
                'slug' => 'acta-independencia-1821',
                'excerpt' => 'La independencia se proclamó el 15 de septiembre de 1821 con la firma en la ciudad de Guatemala.',
                'era' => 'Siglo XIX',
                'leading_figure' => 'Miguel de Larreynaga',
                'content' => <<< 'TEXT'
El 15 de septiembre de 1821, los representantes de las provincias centroamericanas rubricaron en la ciudad de Guatemala el acta que declaraba su separación formal de la monarquía española. Miguel de Larreynaga, originario de León, integró la comisión nicaragüense que avaló el histórico documento.

La noticia se difundió por todo el territorio a través de cabildos abiertos y proclamas impresas. En León y Granada se organizaron celebraciones cívicas, mientras que las autoridades locales discutieron cómo estructurar el nuevo gobierno provincial. El acta marcó el inicio de un complejo proceso de organización republicana en la región.
TEXT,
                'cover_path' => 'covers/historia-independencia.svg',
                'category_slug' => 'independencia-nicaragua',
                'author_email' => 'sofia.independencia@redline.test',
            ],
            [
                'title' => 'Triunfo de la Revolución Sandinista',
                'slug' => 'triunfo-revolucion-1979',
                'excerpt' => 'El 19 de julio de 1979 el Frente Sandinista puso fin a la dictadura somocista.',
                'era' => 'Siglo XX',
                'leading_figure' => 'Frente Sandinista de Liberación Nacional',
                'content' => <<< 'TEXT'
Tras más de cuatro décadas de control somocista, las columnas del Frente Sandinista de Liberación Nacional ingresaron a Managua el 19 de julio de 1979. La insurrección final unió a las guerrillas urbanas, las comunidades campesinas y un amplio movimiento estudiantil.

El triunfo marcó el inicio de un proyecto de transformación social que impulsó campañas de alfabetización, reforma agraria y participación comunitaria. También dio paso a una década de tensiones geopolíticas en plena Guerra Fría, con la firma de los Acuerdos de Paz de Esquipulas como hito posterior.
TEXT,
                'cover_path' => 'covers/historia-revolucion.svg',
                'category_slug' => 'revolucion-sandinista',
                'author_email' => 'ricardo.sandino@redline.test',
            ],
            [
                'title' => 'El Güegüense, Patrimonio de la Humanidad',
                'slug' => 'el-gueguense-patrimonio',
                'excerpt' => 'Obra teatral mestiza que combina sátira colonial, música de chirimía y bailes tradicionales.',
                'era' => 'Época colonial',
                'leading_figure' => 'El Güegüense',
                'content' => <<< 'TEXT'
El Güegüense o Macho Ratón es considerado el primer texto dramático mesoamericano escrito en dos idiomas: náhuatl y castellano. Inmortaliza la picardía del comerciante indígena que desafía la autoridad colonial mediante diálogos cargados de ironía.

Cada enero, las calles de Diriamba se llenan de mascareros, músicos y bailarines que recrean la historia. En 2005 la UNESCO declaró la festividad Patrimonio Cultural Inmaterial de la Humanidad, reconociendo su valor como espejo de la identidad nicaragüense.
TEXT,
                'cover_path' => 'covers/historia-gueguense.svg',
                'category_slug' => 'cultura-tradiciones',
                'author_email' => 'camila.cultura@redline.test',
            ],
            [
                'title' => 'Batalla de San Jacinto',
                'slug' => 'batalla-san-jacinto-1856',
                'excerpt' => 'El ejército nicaragüense frenó el avance filibustero de William Walker el 14 de septiembre de 1856.',
                'era' => 'Siglo XIX',
                'leading_figure' => 'José Dolores Estrada',
                'content' => <<< 'TEXT'
La mañana del 14 de septiembre de 1856, un contingente de 160 patriotas comandados por el coronel José Dolores Estrada defendió la hacienda San Jacinto frente a una tropa mayor de mercenarios filibusteros. El legendario lanzamiento de piedra de Andrés Castro desarmó al capitán enemigo y elevó la moral de las fuerzas nicaragüenses.

La victoria permitió reorganizar la resistencia nacional y consolidó la alianza con otros países centroamericanos contra el proyecto esclavista de Walker. Cada año, el sitio histórico recibe actos cívicos, recreaciones y guardias de honor estudiantiles.
TEXT,
                'cover_path' => 'covers/historia-san-jacinto.svg',
                'category_slug' => 'guerras-batallas',
                'author_email' => 'adriana.guerras@redline.test',
            ],
            [
                'title' => 'Rubén Darío y el modernismo',
                'slug' => 'ruben-dario-modernismo',
                'excerpt' => 'El poeta de Metapa revolucionó la poesía en lengua española y la diplomacia cultural.',
                'era' => 'Cambio de siglo XIX-XX',
                'leading_figure' => 'Rubén Darío',
                'content' => <<< 'TEXT'
Félix Rubén García Sarmiento, conocido como Rubén Darío, nació en 1867 en Metapa, hoy Ciudad Darío. Con la publicación de "Azul..." en 1888 inauguró el modernismo literario, movimiento que renovó la métrica, la musicalidad y el uso simbólico del lenguaje.

Darío sirvió como diplomático en Buenos Aires, Madrid y París, difundiendo la cultura nicaragüense y la causa centroamericana. Sus crónicas periodísticas sobre la Primera Guerra Mundial y sus libros "Prosas profanas" y "Cantos de vida y esperanza" siguen inspirando a lectores y docentes.
TEXT,
                'cover_path' => 'covers/historia-ruben-dario.svg',
                'category_slug' => 'arte-literatura',
                'author_email' => 'ernesto.personajes@redline.test',
            ],
            [
                'title' => 'Sabores de Cuaresma en Nicaragua',
                'slug' => 'sabores-cuaresma-nicaragua',
                'excerpt' => 'La temporada cuaresmal rescata recetas con maíz nuevo, pescado seco y frutas en almíbar.',
                'era' => 'Nicaragua contemporánea',
                'leading_figure' => 'Cocineras tradicionales',
                'content' => <<< 'TEXT'
La cocina cuaresmal nicaragüense combina ingredientes frescos y técnicas tradicionales. La sopa de queso de Masaya, el pinol con gasimba y los almíbares de papaya, jocote y mango se preparan colectivamente para compartirlos en familia y con la comunidad.

En Nagarote destaca el güiril, pan dulce elaborado con maíz tiernito, y en León las "chancletas" rellenas de ayote sazón. Escuelas técnicas y colectivos de mujeres documentan actualmente estas recetas para garantizar su transmisión a nuevas generaciones.
TEXT,
                'cover_path' => 'covers/historia-gastronomia.svg',
                'category_slug' => 'gastronomia-tradicional',
                'author_email' => 'martin.educacion@redline.test',
            ],
        ];

        foreach ($histories as $history) {
            DB::table('histories')->updateOrInsert(
                ['slug' => $history['slug']],
                [
                    'user_id' => $authorIds->get($history['author_email']) ?? $authorIds->first(),
                    'category_id' => $categoryIds[$history['category_slug']] ?? null,
                    'title' => $history['title'],
                    'excerpt' => $history['excerpt'],
                    'content' => $history['content'],
                    'cover_path' => $history['cover_path'],
                    'era' => $history['era'] ?? null,
                    'leading_figure' => $history['leading_figure'] ?? null,
                    'published_at' => $now,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );
        }

        $newsItems = [
            [
                'title' => 'Honores a Héroes de San Jacinto',
                'slug' => 'honores-san-jacinto-2025',
                'body' => <<< 'TEXT'
El 14 de septiembre se realizó el acto central por el 169 aniversario de la Batalla de San Jacinto en la histórica hacienda tipitepa. Cadetes del Ejército, estudiantes de secundaria y delegaciones municipales depositaron ofrendas florales en memoria de José Dolores Estrada y Andrés Castro.

La jornada incluyó una representación teatral de la gesta y la entrega de reconocimientos a cronistas locales que resguardan la memoria del hecho histórico. El Ministerio de Educación anunció una ruta patrimonial para que los bachilleres visiten el sitio durante el mes patrio.
TEXT,
                'cover_path' => 'covers/noticia-san-jacinto.svg',
                'author_email' => 'hector.eventos@redline.test',
            ],
            [
                'title' => 'UNESCO reconoce tradiciones nicaragüenses',
                'slug' => 'unesco-tradiciones-2025',
                'body' => <<< 'TEXT'
La Organización de las Naciones Unidas para la Educación, la Ciencia y la Cultura incorporó oficialmente a "La Gigantona" y "El Enano Cabezón" en el registro de buenas prácticas de salvaguardia del patrimonio inmaterial. El expediente fue presentado por gestores culturales de León y Masaya.

El reconocimiento destaca la creatividad de los artesanos que elaboran las estructuras de madera y papel, así como la transmisión oral de los versos satíricos que acompañan las presentaciones. Autoridades municipales anunciaron la apertura de talleres gratuitos para nuevas generaciones de bailantes.
TEXT,
                'cover_path' => 'covers/noticia-unesco.svg',
                'author_email' => 'camila.cultura@redline.test',
            ],
            [
                'title' => 'Festival Internacional de Poesía regresa a Granada',
                'slug' => 'festival-poesia-granada-2025',
                'body' => <<< 'TEXT'
Los organizadores del Festival Internacional de Poesía confirmaron la participación de 40 delegaciones para la edición XVIII, dedicada a la figura de Ernesto Cardenal. Habrá recitales en las calles empedradas de Granada, conversatorios sobre poesía mística y visitas guiadas al Museo Convento San Francisco.

El evento contempla un circuito escolar en el que poetas invitados impartirán talleres en colegios públicos de Masaya, Rivas y Carazo. La inauguración será acompañada por la Camerata Bach y un recital coral de niños bilingües.
TEXT,
                'cover_path' => 'covers/noticia-poesia.svg',
                'author_email' => 'ernesto.personajes@redline.test',
            ],
            [
                'title' => 'Museo Nacional inaugura sala arqueológica',
                'slug' => 'museo-nacional-sala-arqueologica-2025',
                'body' => <<< 'TEXT'
El Instituto Nicaragüense de Cultura presentó la nueva sala arqueológica permanente del Museo Nacional, con piezas prehispánicas procedentes de Ometepe, Chontales, Nueva Segovia y el Caribe Sur. La muestra integra urnas funerarias, metates ceremoniales y petrograbados restaurados en colaboración con la Universidad Nacional Autónoma de Nicaragua.

La sala incorpora recursos interactivos para estudiantes: mapas táctiles, audioguías en lengua miskita y vídeos que recrean las técnicas de tallado volcánico. El proyecto recibió respaldo de la Cooperación Española y del Programa Ibermuseos.
TEXT,
                'cover_path' => 'covers/noticia-museo.svg',
                'author_email' => 'valeria.patrimonio@redline.test',
            ],
            [
                'title' => 'Programa educativo rescata recetas de Cuaresma',
                'slug' => 'programa-recetas-cuaresma-2025',
                'body' => <<< 'TEXT'
Estudiantes de secundaria técnica en Nagarote publicaron un recetario digital con platos tradicionales de Cuaresma como la sopa de queso, el almíbar de mango sazón y los tamalitos de maíz nuevo. Las investigaciones recopilan historias familiares, fotografías y tiempos de cocción para preservar sabores patrimoniales.

El Ministerio de Educación anunció que el material se integrará a la Estrategia Nacional de Cultura Alimentaria y estará disponible en bibliotecas públicas. Cocineras tradicionales impartirán talleres presenciales para recrear los platillos durante la Semana Santa.
TEXT,
                'cover_path' => 'covers/noticia-cuaresma.svg',
                'author_email' => 'martin.educacion@redline.test',
            ],
        ];

        foreach ($newsItems as $news) {
            DB::table('news')->updateOrInsert(
                ['slug' => $news['slug']],
                [
                    'user_id' => $authorIds->get($news['author_email']) ?? $authorIds->first(),
                    'title' => $news['title'],
                    'body' => $news['body'],
                    'cover_path' => $news['cover_path'],
                    'published_at' => $now,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );
        }

        $events = [
            [
                'title' => 'Acto de Independencia',
                'description' => 'Ceremonia en Plaza de la Independencia en León con bandas musicales y lectura del acta.',
                'start_at' => '2025-09-15 08:00:00',
                'end_at' => '2025-09-15 12:00:00',
                'location' => 'León, Nicaragua',
                'author_email' => 'hector.eventos@redline.test',
            ],
            [
                'title' => 'Festival del Güegüense',
                'description' => 'Presentaciones culturales, talleres de máscaras y desfiles de comparsas en Diriamba.',
                'start_at' => '2025-01-20 16:00:00',
                'end_at' => '2025-01-20 21:00:00',
                'location' => 'Diriamba, Carazo',
                'author_email' => 'camila.cultura@redline.test',
            ],
            [
                'title' => 'Conmemoración Batalla de San Jacinto',
                'description' => 'Acto cívico y cultural en homenaje a la gesta histórica con participación estudiantil.',
                'start_at' => '2025-09-14 09:00:00',
                'end_at' => '2025-09-14 12:00:00',
                'location' => 'Hacienda San Jacinto, Tipitapa',
                'author_email' => 'adriana.guerras@redline.test',
            ],
            [
                'title' => 'Purísima en León',
                'description' => 'Celebración tradicional de la Gritería en honor a la Virgen María con altares populares.',
                'start_at' => '2025-12-07 18:00:00',
                'end_at' => '2025-12-07 23:00:00',
                'location' => 'León, Nicaragua',
                'author_email' => 'valeria.patrimonio@redline.test',
            ],
            [
                'title' => 'Festival Internacional de Poesía de Granada',
                'description' => 'Lecturas, talleres y conversatorios en la ciudad colonial durante una semana.',
                'start_at' => '2025-02-13 10:00:00',
                'end_at' => '2025-02-19 20:00:00',
                'location' => 'Granada, Nicaragua',
                'author_email' => 'ernesto.personajes@redline.test',
            ],
            [
                'title' => 'Feria del Güiril de Nagarote',
                'description' => 'Celebración gastronómica dedicada al tradicional pan de maíz y dulces locales.',
                'start_at' => '2025-07-05 09:00:00',
                'end_at' => '2025-07-05 18:00:00',
                'location' => 'Nagarote, León',
                'author_email' => 'martin.educacion@redline.test',
            ],
            [
                'title' => 'Gritería Chiquita en León',
                'description' => 'Procesiones, rezos y brindis en honor a la Virgen de los Ángeles.',
                'start_at' => '2025-08-14 17:00:00',
                'end_at' => '2025-08-14 23:30:00',
                'location' => 'León, Nicaragua',
                'author_email' => 'valeria.patrimonio@redline.test',
            ],
        ];

        foreach ($events as $event) {
            DB::table('events')->updateOrInsert(
                [
                    'title' => $event['title'],
                    'start_at' => Carbon::parse($event['start_at']),
                ],
                [
                    'user_id' => $authorIds->get($event['author_email']) ?? $authorIds->first(),
                    'description' => $event['description'],
                    'end_at' => $event['end_at'] ? Carbon::parse($event['end_at']) : null,
                    'location' => $event['location'],
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );
        }

        $libraryItems = [
            [
                'title' => 'Acta de Independencia de Centroamérica',
                'type' => 'image',
                'description' => 'Infografía con extractos del acta original firmada el 15 de septiembre de 1821.',
                'file_path' => 'library/acta1821.svg',
                'cover_path' => 'library/acta1821.svg',
                'external_url' => null,
                'video_url' => null,
                'video_caption' => null,
                'author_email' => 'javier.library@redline.test',
            ],
            [
                'title' => 'El Güegüense - Texto completo',
                'type' => 'image',
                'description' => 'Resumen visual con escenas, personajes y vocablos náhuatl del teatro mestizo.',
                'file_path' => 'library/gueguense.svg',
                'cover_path' => 'library/gueguense.svg',
                'external_url' => null,
                'video_url' => null,
                'video_caption' => null,
                'author_email' => 'patricia.archivo@redline.test',
            ],
            [
                'title' => 'Fotografías de la Revolución Sandinista',
                'type' => 'image',
                'description' => 'Collage cronológico de imágenes de 1978-1979 con créditos de prensa comunitaria.',
                'file_path' => 'library/revolucion-imagenes.svg',
                'cover_path' => 'library/revolucion-imagenes.svg',
                'external_url' => null,
                'video_url' => null,
                'video_caption' => null,
                'author_email' => 'ricardo.sandino@redline.test',
            ],
            [
                'title' => 'Catedral de León - Patrimonio Mundial',
                'type' => 'image',
                'description' => 'Panel ilustrado que destaca los vitrales, bóvedas y patrimonio documental de la basílica.',
                'file_path' => 'library/catedral-leon.svg',
                'cover_path' => 'library/catedral-leon.svg',
                'external_url' => null,
                'video_url' => null,
                'video_caption' => null,
                'author_email' => 'valeria.patrimonio@redline.test',
            ],
            [
                'title' => 'Podcast Historia de las Segovias',
                'type' => 'image',
                'description' => 'Guía visual con episodios recomendados sobre la resistencia campesina de las Segovias.',
                'file_path' => 'library/podcast-segovias.svg',
                'cover_path' => 'library/podcast-segovias.svg',
                'external_url' => null,
                'video_url' => null,
                'video_caption' => null,
                'author_email' => 'ricardo.sandino@redline.test',
            ],
            [
                'title' => 'Recetario de Cuaresma nicaragüense',
                'type' => 'image',
                'description' => 'Lámina con ingredientes clave y pasos resumidos de la sopa de queso y postres en miel.',
                'file_path' => 'library/recetario-cuaresma.svg',
                'cover_path' => 'library/recetario-cuaresma.svg',
                'external_url' => null,
                'video_url' => null,
                'video_caption' => null,
                'author_email' => 'martin.educacion@redline.test',
            ],
            [
                'title' => 'Documental Río San Juan',
                'type' => 'video',
                'description' => 'Serie documental sobre biodiversidad y rutas históricas del río San Juan.',
                'file_path' => null,
                'cover_path' => 'library/revolucion-imagenes.svg',
                'external_url' => null,
                'video_url' => 'https://www.youtube.com/watch?v=abc123XYZ89',
                'video_caption' => 'Documental accesible con locución descriptiva sobre el río San Juan y sus comunidades.',
                'author_email' => 'javier.library@redline.test',
            ],
        ];

        foreach ($libraryItems as $item) {
            DB::table('library_items')->updateOrInsert(
                ['title' => $item['title']],
                [
                    'user_id' => $authorIds->get($item['author_email']) ?? $authorIds->first(),
                    'type' => $item['type'],
                    'description' => $item['description'],
                    'file_path' => $item['file_path'],
                    'cover_path' => $item['cover_path'],
                    'external_url' => $item['external_url'],
                    'video_url' => $item['video_url'],
                    'video_caption' => $item['video_caption'],
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );
        }

        $games = [
                [
                    'key' => 'independencia',
                    'title' => 'Quiz de Independencia',
                    'description' => 'Pon a prueba tus conocimientos sobre el proceso independentista de Nicaragua.',
                    'points_per_question' => 10,
                    'type' => 'quiz',
                    'author_email' => 'natalia.quiz@redline.test',
                    'questions' => [
                        [
                            'statement' => '¿En qué año se firmó el Acta de Independencia de Centroamérica?',
                            'options' => [
                                ['text' => '1821', 'is_correct' => true],
                                ['text' => '1856', 'is_correct' => false],
                                ['text' => '1893', 'is_correct' => false],
                                ['text' => '1912', 'is_correct' => false],
                            ],
                        ],
                        [
                            'statement' => '¿Qué ciudad fue sede de la firma del Acta de Independencia que incluyó a Nicaragua?',
                            'options' => [
                                ['text' => 'León', 'is_correct' => false],
                                ['text' => 'Guatemala', 'is_correct' => true],
                                ['text' => 'San Salvador', 'is_correct' => false],
                                ['text' => 'Tegucigalpa', 'is_correct' => false],
                            ],
                        ],
                        [
                            'statement' => '¿Qué documento ratificó la anexión de Nicaragua al Imperio Mexicano en 1822?',
                            'options' => [
                                ['text' => 'Plan de Ayutla', 'is_correct' => false],
                                ['text' => 'Plan de Iguala', 'is_correct' => true],
                                ['text' => 'Acta de Los Nublados', 'is_correct' => false],
                                ['text' => 'Tratado de Guadalupe', 'is_correct' => false],
                            ],
                        ],
                    ],
                ],
                [
                    'key' => 'revolucion',
                    'title' => 'Quiz de Revolución Sandinista',
                    'description' => 'Preguntas sobre el Frente Sandinista y la lucha contra la dictadura somocista.',
                    'points_per_question' => 15,
                    'type' => 'quiz',
                    'author_email' => 'ricardo.sandino@redline.test',
                    'questions' => [
                        [
                            'statement' => '¿Quién fue el último dictador de la familia Somoza?',
                            'options' => [
                                ['text' => 'Luis Somoza Debayle', 'is_correct' => false],
                                ['text' => 'Anastasio Somoza García', 'is_correct' => false],
                                ['text' => 'Anastasio Somoza Debayle', 'is_correct' => true],
                                ['text' => 'Carlos Manuel Jarquin', 'is_correct' => false],
                            ],
                        ],
                        [
                            'statement' => '¿Qué barrio indígena protagonizó la insurrección de febrero de 1978?',
                            'options' => [
                                ['text' => 'Monimbó', 'is_correct' => true],
                                ['text' => 'Sutiaba', 'is_correct' => false],
                                ['text' => 'Masaya Centro', 'is_correct' => false],
                                ['text' => 'Las Brisas', 'is_correct' => false],
                            ],
                        ],
                        [
                            'statement' => '¿Cómo se llamó la ofensiva final que culminó el 19 de julio de 1979?',
                            'options' => [
                                ['text' => 'Ofensiva Carlos Fonseca', 'is_correct' => true],
                                ['text' => 'Operación Sandino', 'is_correct' => false],
                                ['text' => 'Operación Victoria', 'is_correct' => false],
                                ['text' => 'Operación 19 de Julio', 'is_correct' => false],
                            ],
                        ],
                    ],
                ],
                [
                    'key' => 'san-jacinto',
                    'title' => 'Quiz Batalla de San Jacinto',
                    'description' => 'Preguntas sobre la defensa nicaragüense contra los filibusteros de William Walker.',
                    'points_per_question' => 20,
                    'type' => 'memoria',
                    'author_email' => 'adriana.guerras@redline.test',
                    'questions' => [
                        [
                            'statement' => '¿Qué héroe nicaragüense lanzó una pedrada decisiva durante la batalla?',
                            'options' => [
                                ['text' => 'Andrés Castro', 'is_correct' => true],
                                ['text' => 'José Dolores Estrada', 'is_correct' => false],
                                ['text' => 'Benito Morales', 'is_correct' => false],
                                ['text' => 'Francisco Buitrago', 'is_correct' => false],
                            ],
                        ],
                        [
                            'statement' => '¿Cuál era el objetivo principal de William Walker en Nicaragua?',
                            'options' => [
                                ['text' => 'Expandir el ferrocarril', 'is_correct' => false],
                                ['text' => 'Fundar colonias agrícolas', 'is_correct' => false],
                                ['text' => 'Controlar Centroamérica para anexarla como un estado esclavista', 'is_correct' => true],
                                ['text' => 'Crear una república indígena', 'is_correct' => false],
                            ],
                        ],
                        [
                            'statement' => '¿Qué fecha se conmemora la Batalla de San Jacinto en Nicaragua?',
                            'options' => [
                                ['text' => '14 de septiembre', 'is_correct' => true],
                                ['text' => '15 de septiembre', 'is_correct' => false],
                                ['text' => '19 de julio', 'is_correct' => false],
                                ['text' => '21 de junio', 'is_correct' => false],
                            ],
                        ],
                    ],
                ],
                [
                    'key' => 'patrimonio',
                    'title' => 'Quiz Patrimonio UNESCO',
                    'description' => 'Pon a prueba tus conocimientos sobre los sitios y tradiciones declarados Patrimonio de la Humanidad.',
                    'points_per_question' => 15,
                    'type' => 'memoria',
                    'author_email' => 'valeria.patrimonio@redline.test',
                    'questions' => [
                        [
                            'statement' => '¿Cuál es la obra teatral nicaragüense reconocida como Patrimonio de la Humanidad?',
                            'options' => [
                                ['text' => 'El Güegüense', 'is_correct' => true],
                                ['text' => 'La Purísima', 'is_correct' => false],
                                ['text' => 'La Gigantona', 'is_correct' => false],
                                ['text' => 'El Toro Huaco', 'is_correct' => false],
                            ],
                        ],
                        [
                            'statement' => '¿Qué año fue declarada Patrimonio de la Humanidad la Catedral de León?',
                            'options' => [
                                ['text' => '2000', 'is_correct' => false],
                                ['text' => '2011', 'is_correct' => true],
                                ['text' => '1995', 'is_correct' => false],
                                ['text' => '1987', 'is_correct' => false],
                            ],
                        ],
                        [
                            'statement' => '¿Qué reserva nicaragüense es una Biosfera reconocida por la UNESCO desde 1997?',
                            'options' => [
                                ['text' => 'Reserva Miraflor', 'is_correct' => false],
                                ['text' => 'Isla de Ometepe', 'is_correct' => false],
                                ['text' => 'Bosawás', 'is_correct' => true],
                                ['text' => 'Volcán Masaya', 'is_correct' => false],
                            ],
                        ],
                    ],
                ],
                [
                    'key' => 'ruben-dario',
                    'title' => 'Quiz sobre Rubén Darío',
                    'description' => 'Preguntas sobre la vida y obra del Príncipe de las Letras Castellanas.',
                    'points_per_question' => 12,
                    'type' => 'quiz',
                    'author_email' => 'ernesto.personajes@redline.test',
                    'questions' => [
                        [
                            'statement' => '¿En qué ciudad nació Rubén Darío?',
                            'options' => [
                                ['text' => 'León', 'is_correct' => false],
                                ['text' => 'Granada', 'is_correct' => false],
                                ['text' => 'Metapa (hoy Ciudad Darío)', 'is_correct' => true],
                                ['text' => 'Chinandega', 'is_correct' => false],
                            ],
                        ],
                        [
                            'statement' => '¿Cuál es la obra considerada el inicio del modernismo dariano?',
                            'options' => [
                                ['text' => 'Prosas profanas', 'is_correct' => false],
                                ['text' => 'Cantos de vida y esperanza', 'is_correct' => false],
                                ['text' => 'Azul...', 'is_correct' => true],
                                ['text' => 'Tierras solares', 'is_correct' => false],
                            ],
                        ],
                        [
                            'statement' => '¿Qué cargo diplomático ocupó Rubén Darío en Europa?',
                            'options' => [
                                ['text' => 'Embajador en Francia', 'is_correct' => false],
                                ['text' => 'Cónsul de Nicaragua en París y Madrid', 'is_correct' => true],
                                ['text' => 'Agregado cultural en Roma', 'is_correct' => false],
                                ['text' => 'Embajador en España', 'is_correct' => false],
                            ],
                        ],
                    ],
                ],
                [
                    'key' => 'gastronomia',
                    'title' => 'Quiz Gastronomía de Cuaresma',
                    'description' => 'Identifica ingredientes y tradiciones culinarias nicaragüenses durante la vigilia.',
                    'points_per_question' => 8,
                    'type' => 'quiz',
                    'author_email' => 'martin.educacion@redline.test',
                    'questions' => [
                        [
                            'statement' => '¿Cuál es el ingrediente principal del tradicional güiril de Nagarote?',
                            'options' => [
                                ['text' => 'Yuca', 'is_correct' => false],
                                ['text' => 'Maíz nuevo', 'is_correct' => true],
                                ['text' => 'Plátano verde', 'is_correct' => false],
                                ['text' => 'Ayote', 'is_correct' => false],
                            ],
                        ],
                        [
                            'statement' => '¿Qué postre de Semana Santa combina frutas en almíbar con miel de rapadura?',
                            'options' => [
                                ['text' => 'Sopa de queso', 'is_correct' => false],
                                ['text' => 'Almíbar de jocote', 'is_correct' => true],
                                ['text' => 'Tres leches', 'is_correct' => false],
                                ['text' => 'Bienmesabe', 'is_correct' => false],
                            ],
                        ],
                        [
                            'statement' => '¿Qué pescado se utiliza comúnmente en la sopa de queso nicaragüense?',
                            'options' => [
                                ['text' => 'Robalo', 'is_correct' => false],
                                ['text' => 'Sardina seca', 'is_correct' => true],
                                ['text' => 'Tilapia', 'is_correct' => false],
                                ['text' => 'Carpa', 'is_correct' => false],
                            ],
                        ],
                    ],
                ],
            ];

            foreach ($games as $gameData) {
                $game = Game::updateOrCreate(
                    ['title' => $gameData['title']],
                    [
                        'user_id' => $authorIds->get($gameData['author_email']) ?? $authorIds->first(),
                        'description' => $gameData['description'],
                        'points_per_question' => $gameData['points_per_question'],
                        'type' => $gameData['type'] ?? 'quiz',
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]
                );

                $statements = collect($gameData['questions'])->pluck('statement')->all();
                $game->questions()->whereNotIn('statement', $statements)->delete();

                foreach ($gameData['questions'] as $questionData) {
                    $question = $game->questions()->updateOrCreate(
                        ['statement' => $questionData['statement']],
                        ['statement' => $questionData['statement']]
                    );

                    $question->options()->delete();
                    foreach ($questionData['options'] as $optionData) {
                        $question->options()->create([
                            'text' => $optionData['text'],
                            'is_correct' => $optionData['is_correct'],
                        ]);
                    }
                }
            }
    }
}
