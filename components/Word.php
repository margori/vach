<?php

namespace app\components;

use app\models\Team;
use PhpOffice\PhpWord\Element\Section;
use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\Shared\Html;
use Yii;
use app\models\Wheel;
use yii\helpers\ArrayHelper;
use PhpOffice\PhpWord\PhpWord;

class Word
{
    private static $team;
    private static $phpWord;
    private static $section;
    private static $paragraph;
    private static $cell_style;
    private static $bad_cell;
    private static $regular_cell;
    private static $good_cell;
    public static $paths;

    public static function create($team)
    {
        self::$team = $team;

        self::$phpWord = new PhpWord();
        Settings::setOutputEscapingEnabled(true);

        self::setupStyles();

        // New portrait section
        self::$section = self::$phpWord->addSection([
            'marginLeft' => 1700,
            'marginRight' => 1100,
            'marginTop' => 1100,
            'marginBottom' => 1100,
            'headerHeight' => 500,
            'footerHeight' => 500,
        ]);

        $footer = self::$section->addFooter();
        $table = $footer->addTable();
        $table->addRow();
        $table
            ->addCell(8000)
            ->addImage(
                Yii::getAlias('@app/web/images/brands/footer-gray.png'),
                ['width' => 400]
            );
        $table->addCell(1200)->addPreserveText(
            'Pag // {PAGE}',
            [
                'size' => '10',
            ],
            [
                'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::RIGHT,
                'space' => ['before' => 0, 'after' => 0],
                'indentation' => ['firstLine' => 0],
            ]
        );

        self::addFrontPage();

        self::addTeamPage();
        self::addIndex();
        self::addIntroduction();
        self::addFundaments();
        self::addTeamAnalisys();
        self::addIndividualAnalisys();
        self::addSummary();
        self::addActionPlan();

        return self::$phpWord;
    }

    private static function setupStyles()
    {
        //self::$phpWord->addFontStyle(self::COMMON_FONT, ['size' => 12, 'spaceAfter' => 240]);

        self::$phpWord->setDefaultParagraphStyle([
            'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::BOTH,
            'space' => ['before' => 0, 'after' => 120],
            'indentation' => ['firstLine' => 300],
        ]);

        self::$paragraph = [
            'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::BOTH,
            'space' => ['before' => 0, 'after' => 120],
            'indentation' => ['firstLine' => 300],
        ];

        self::$cell_style = ['valign' => 'center'];
        self::$bad_cell = ArrayHelper::merge(self::$cell_style, [
            'bgColor' => 'f2dede',
        ]);
        self::$regular_cell = ArrayHelper::merge(self::$cell_style, [
            'bgColor' => 'fcf8e3',
        ]);
        self::$good_cell = ArrayHelper::merge(self::$cell_style, [
            'bgColor' => 'dff0d8',
        ]);

        self::$phpWord->addTitleStyle(
            1,
            ['bold' => true, 'size' => 18, 'color' => 'FF0000'],
            [
                'spaceBefore' => 240,
                'spaceAfter' => 120,
                'indentation' => ['firstLine' => 0],
            ]
        );
        self::$phpWord->addTitleStyle(
            2,
            ['bold' => true, 'size' => 16, 'color' => 'FF0000'],
            [
                'spaceBefore' => 180,
                'spaceAfter' => 120,
                'indentation' => ['firstLine' => 0],
            ]
        );
        self::$phpWord->addTitleStyle(
            3,
            ['bold' => true, 'size' => 14, 'color' => 'FF0000'],
            [
                'spaceBefore' => 120,
                'spaceAfter' => 120,
                'indentation' => ['firstLine' => 0],
            ]
        );
    }

    private static function i($image)
    {
        self::$section->addImage(Yii::getAlias($image), [
            'positioning' => \PhpOffice\PhpWord\Style\Image::POSITION_RELATIVE,
            'marginTop' => 0,
            'marginLeft' => 0,
            'width' => 455,
            'wrappingStyle' => \PhpOffice\PhpWord\Style\Image::WRAP_TOPBOTTOM,
        ]);
    }

    private static function t($text)
    {
        self::$section->addText($text, null, self::$paragraph);
    }

    private static function addFrontPage()
    {
        self::i('@app/web/images/brands/01-frontpage.png');
    }

    private static function addTeamPage()
    {
        self::$section->addPageBreak();

        self::i('@app/web/images/brands/02-VACH.png');

        self::$section->addText(
            self::$team->company->name,
            [
                'size' => 16,
                'bold' => true,
            ],
            [
                'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER,
            ]
        );
        self::$section->addText(
            self::$team->name,
            [
                'size' => 14,
                'bold' => true,
            ],
            [
                'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER,
            ]
        );
        self::$section->addText(
            Yii::t('report', 'Report') .
                ' ' .
                Yii::$app->formatter->asDate('now'),
            [
                'size' => 12,
                'bold' => true,
            ],
            [
                'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER,
            ]
        );

        $space = ['space' => ['before' => 0, 'after' => 0]];

        self::$section->addText(
            Yii::t('team', 'Company') . ': ' . self::$team->company->name,
            null,
            $space
        );
        self::$section->addText(
            Yii::t('team', 'Team') . ': ' . self::$team->name,
            null,
            $space
        );
        self::$section->addText(
            Yii::t('user', 'Coach') . ': ' . self::$team->coach->fullname,
            null,
            $space
        );
        self::$section->addText(
            Yii::t('team', 'Sponsor') . ': ' . self::$team->sponsor->fullname,
            null,
            $space
        );
        self::$section->addText('', null, ['spaceAfter' => 0]);
        self::$section->addText(
            Yii::t('report', 'Natural Team') . ':',
            null,
            $space
        );
        foreach (self::$team->activeMemberList as $id => $name) {
            self::$section->addListItem($name, null, null, null, $space);
        }
    }

    private static function addIndex()
    {
        self::$section->addPageBreak();

        // Add text elements
        self::$section->addTitle(Yii::t('report', 'Index'), 1);
        $toc = self::$section->addTOC(['size' => 12]);
    }

    private static function addIntroduction()
    {
        self::$section->addPageBreak();
        self::i('@app/web/images/brands/03-introduction.png');
        self::$section->addPageBreak();

        self::$section->addTitle(Yii::t('report', 'Introduction'), 1);
        \PhpOffice\PhpWord\Shared\Html::addHtml(
            self::$section,
            self::$team->report->introduction
        );
    }

    private static function addFundaments()
    {
        self::$section->addPageBreak();
        self::i('@app/web/images/brands/04-fundaments.png');
        self::$section->addPageBreak();

        self::$section->addTitle(Yii::t('report', 'Fundaments'), 1);

        self::$section->addTitle(
            '¿Qué es el Coaching por Competencias (CPC)?',
            2
        );
        self::t(
            'El Coaching por Competencias® es un método de gestión práctico y eficaz para alinear el desempeño individual con el Performance Management de la Organización, dejando emerger el inconsciente de los equipos naturales, promoviendo la conciencia y la responsabilidad, y con ello la eficiencia y productividad de las personas.'
        );
        self::t(
            'Para nosotros, encontrar una forma económica, eficiente y humana de valorar el Capital Humano es un propósito coherente con lo que predicamos, reconociendo que los Valores humanos, en términos de conciencia y responsabilidad, generan y consolidan vínculos; entendiendo como vínculo a la misma productividad, que se apoya en lo que hace cada persona participante de un proceso y de las relaciones complejas que se establecen en el ámbito donde se desempeñan, para satisfacer sus propias necesidades, las del Grupo al que pertenece, y las necesidades mismas de la Organización, con un impacto en el ámbito social, que esto también conlleva; y por ello, nuestro objetivo primordial de trabajo, será siempre:'
        );
        self::t(
            '“...evaluar el grado de Conciencia y Responsabilidad individual y grupal de las personas dentro de un contexto Organizacional, tendiente a producir un plan de acción integral que nos permita llevar a cabo una gestión de desempeño sostenible en el tiempo...”'
        );
        self::t(
            'Es decir, construir una herramienta para que la Organización se vea a sí misma a partir de las personas que consolidan su Cultura Organizacional, permitiéndole a ellas mismas, a sus grupos de pertenencia y a la propia Organización, distinguir los impactos organizacionales con un máximo nivel de interpretación, aplicando un mismo instrumento replicable y confiable, en un modelo de gestión sistémico, integral y sostenible.'
        );

        self::$section->addTitle('¿Por qué sistémico?', 2);
        self::t(
            'Porque hace referencia a las relaciones intra e intersubjetivas de las Organizaciones, donde grupos de personas en interacción constituyen subsistemas basados en los encuentros con ellos mismos, que reciben inputs de otros subsistemas y en los que, a su vez, sus outputs, alimentan a otros sistemas; y así cada cual hace su trabajo, desde su ámbito de acción, desde lo que comprende que hace (Conciencia), y desde lo que sabe que tiene que hacerse cargo y responder (Responsabilidad).'
        );
        self::t(
            'En esta dinámica, ajustar la forma en la que cada integrante se relaciona, a partir de la manera cómo percibe a cada Individuo en interacción, a su Grupo y a la Organización en su conjunto, facilitará el desempeño en cada uno de los tres planos de impacto (Individual/Grupal/Organizacional) de la herramienta. Estas redes de interacciones interpersonales (Subsistemas) conforman el macrosistema organizacional, donde será preciso identificar el nivel de complejidad vincular de una problemática determinada. El encuadre con el que identificamos dicha problemática y trabajamos sobre ella es lo que reconocemos como impactos organizacionales.'
        );

        self::$section->addTitle('¿Por qué integral y sostenible?', 2);
        self::t(
            'Medir el desempeño desde una visión integral y sistémica, permitirá obtener máxima asertividad en los procesos de cualificación del capital humano, en la medida que la herramienta permite obtener cualitativa y cuantitativamente, el grado de conciencia y el nivel de responsabilidad de cada Subsistema, desde la percepción de su propia realidad; y esto, desde la perspectiva del trabajo con el capital humano organizacional, permite en forma fáctica y precisa:'
        );
        self::$section->addListItem(
            'Evaluar las Competencias (manifestaciones de los comportamientos observables), encuadradas éstas por ciertos indicadores/vectores integrados sistémicamente y basadas sobre un Modelo de la Gestión por Desempeño, elegidas como las más representativas a nivel nacional e internacional por los distintos especialistas en la materia (ver Cuadro Integral de Competencias)'
        );
        self::$section->addListItem(
            'Identificar el impacto real que estos “factores de desvíos” tienen actualmente, en base a los estándares del rendimiento que exige el Negocio.'
        );
        self::$section->addListItem(
            'Prever una Gestión de Capacitación Organizacional, permitiéndonos aportar a las redefiniciones de las estrategias del negocio, conforme a las potencialidades y debilidades reales del individuo/equipo, bajo una concepción integral.'
        );
        self::$section->addListItem(
            'Y por último, definir y establecer las bases para un Plan de Acción integral, que permita trabajar sobre las personas y equipos, en forma específica, conforme a la realidad observada a partir de la aplicación de dicha herramienta.'
        );
        self::t(
            'En el cuadro vemos cómo se van relacionando – o proyectando- cada una de las competencias individuales en lo grupal y luego en lo organizacional; por ejemplo, veamos ahora el Cuadro Integral de Competencias para poder observar cómo se relacionan estas competencias individuales con las grupales, y a su vez, con las organizacionales; todas asociadas con cada una de las ocho dimensiones del Cuadro Integrado del Ser, del Modelo Coaching Psicológico Integral®.'
        );
        self::i('@app/web/images/integration_table.png');
        self::t(
            'En el cuadro vemos cómo se van relacionando – o proyectando- cada una de las competencias individuales en lo grupal y luego en lo organizacional; por ejemplo, si uno de los miembros del equipo obtiene en su rueda integral un valor bajo en su dimensión “Mental”, esto repercutirá muy posiblemente en la competencia grupal de “Comunicación”, y ésta, a su vez, en la Competencia Organizacional “Resolución de Conflictos”. De este modo vemos cuál es el impacto que causa la Ley Causa-Efecto en los comportamientos de los individuos dentro de las organizaciones.'
        );
        self::$section->addText(
            'Como es adentro es afuera, y lo que sucede interiormente en el individuo, en cada una de sus dimensiones, impacta directamente es su desempeño grupal y organizacional.',
            ['bold' => true],
            self::$paragraph
        );

        self::$section->addTitle(
            'El Método (Dialéctico-proyectivo-perceptivo)',
            2
        );
        self::t(
            'Como experiencia ampliamente investigada se encuentra aquello que sería la co-construcción de la realidad: la manera de cómo, con otras personas, compartimos nuestro modo de ver el mundo, según sea nuestra ubicación socio-psico-bio- cultural e histórica (con esto nos referimos al momento y al lugar en el que nacemos, que hace que miremos al mundo con los ojos que lo miramos). Esto se relaciona con el término Constructivismo, que nos explica cómo exploramos con otros las posibilidades de interiorizar una serie de programas adaptativos desde que nacemos, para así tener pertenencia, y con esto, un lenguaje que nos brinde las herramientas para interactuar, aprender y sostener una realidad posible con el mismo grupo de influencia; es decir, ser socio-culturales, y al mismo tiempo, ser nosotros mismos.'
        );
        self::t(
            'De esta manera construimos nuestra realidad en conjunto, a partir del encuentro con los otros, haciendo que nuestros problemas humanos sean el distanciamiento entre nuestras maneras de vernos y ver a los demás, en un momento determinado, y eternizando así, casi siempre, dicha experiencia. En esta forma de vivir nuestra vida (dinámica), desde el distanciamiento, para afianzar el recuerdo de la experiencia con el otro —que se realiza mayoritariamente de forma inconsciente—, vamos dándole sentido a nuestro quehacer cotidiano; lo que implica que las relaciones humanas están en un margen de infinita complejidad y diversidad, al distinguir así la importancia de lo individual en este proceso de construcción colectiva.'
        );
        self::t(
            'En este marco de ideas, nuestra propuesta investigativa, además de medir el desempeño de las personas, visualiza la construcción de una metodología que permite ajustar progresivamente las percepciones individuales a medida que cada uno va ampliando conciencia de cada cual y de las implicancias que determinan nuestros actos en los distintos subsistemas, hasta comprender la responsabilidad /productividad que se determina en el interior de la Organización.'
        );

        self::$section->addTitle('¿Cómo se lograría esta meta?', 2);
        self::t(
            'Al referirnos a una metodología de indagación Dialéctica-Proyectiva-Perceptiva, nos referimos a:'
        );
        self::$section->addListItem(
            'Dialéctica: es la acción de establecer diálogos con diversos participantes, los cuales guardan como propósito desarrollar nuevos sentidos interpretativos, sobre diversos fenómenos o experiencias, siguiendo un método y ampliando el conocimiento y los acuerdos para optimizar futuras acciones.'
        );
        self::$section->addListItem(
            'Proyección: fenómeno psicológico de visualizar en el exterior una experiencia del interior, tal cual como lo hace un proyector de imágenes. Lo que psicológicamente sería el hecho de imputar en otras personas, determinadas experiencias, cualidades y características, de las que percibe uno mismo, y que implica una experiencia necesaria para establecer procesos de aprendizaje y auto-descubrimiento para el desarrollo de la Conciencia.'
        );
        self::$section->addListItem(
            'Percepción: refiere al proceso que implica la captación de las sensaciones a través de los sentidos, que pasan a ser integradas y ordenadas por el sistema cognitivo del sujeto, a partir de sus esquemas mentales y creencias aprehendidas en las pautas de la crianza y la experiencia, dando sentido o descartando la información, en términos de ordenadores.'
        );
        self::t(
            'Es así como nuestra definición de la metodología «Dialéctica-Proyectiva-Perceptiva» sería:'
        );
        self::$section->addText(
            '“...el proceso por el cual se observan las percepciones particulares sobre sí mismo y los otros, identificando la diferencia entre las proyecciones individuales y grupales (brechas) en un entorno determinado, facilitando así el ajuste subjetivo hacia una nueva narrativa, en términos de realidad, que establecerá el Grupo para sí mismo...”',
            ['bold' => true],
            self::$paragraph
        );

        self::$section->addTitle('¿Qué sería el ajuste subjetivo?', 2);
        self::t(
            'Si la descripción de lo que consideramos la realidad es a partir de una co-construcción conjunta, ésta, entonces, también determinará la forma en que nos vemos a nosotros mismos, a los otros y a lo otro. Esta descripción posible y comprendida por cada cual bajo sus propias atribuciones condiciona directamente a la Organización, pues ellas mismas implican la construcción de las realidades desde donde el sujeto se define a sí mismo, en cuanto a su propósito de vida, felicidad, auto-realización y desarrollo personal, y es así como aporta desde el propio descubrimiento, de ese sí mismo, a la descripción que hace de los interactuantes que la componen.'
        );
        self::t(
            'Ajustar estas proyecciones y auto-percepciones implica que el sujeto se dé cuenta de lo que “deposita” en el otro, siendo ello propio para sí e indispensable para aumentar su responsabilidad, en la medida que toma las opiniones de sus pares, a la vez que ellos toman las de éste. Estas percepciones particulares, al ser vistas en conjunto, movilizan una diferencia entre la manera en que me percibo y me perciben, que hablarían del ajuste posible intersubjetivo entre las relaciones de las personas.'
        );
        self::t(
            'Este movimiento es complejizado cuando en la aplicación de la metodología de investigación, se observan las relaciones entre las percepciones individuales y grupales frente a la Organización, generando así diversas opiniones en tres subsistemas cada uno más complejo al anterior. Observar la dinámica de estos datos posibilita realizar retroalimentaciones en cada sistema de interacción (Individuo, Grupo y Organización) y, por consiguiente, a cada sistema de interacción, ajustarlo progresivamente aún más, según la percepción individual y grupal, hacia un máximo ajuste con la Cultura Organizacional. Es decir, ajustar es re-acordar, reorientar los acuerdos tácitos y explícitos de las personas que establecen sus relaciones en una Organización, a fin de garantizar que ese reajuste permita alcanzar el necesario estado de equilibrio entre los tres Subsistemas mencionados, permitiendo de este modo no sólo la realización de las personas en el plano laboral, sino la consecución de los objetivos estratégicos del Negocio, en un marco de eficiencia y productividad gestional.'
        );
        self::t(
            'En este orden, y sólo para llevarlo a un ejemplo medible, si yo creo que doy (o soy: si acordamos que dar y ser terminan siendo lo mismo) en algún aspecto un 10 (en una escala de 1-10), pero 15 personas que comparten conmigo, me devuelven que en ese mismo aspecto doy un 5, evidentemente lo que estoy proyectando es un 5 (vuelve lo que doy: por la ley causa-efecto). En este ejemplo, podríamos validar mi realidad como un nivel de 5 y mi creencia como uno de 10.'
        );
        self::t(
            'En este mismo sentido, será lógico pensar que si doy un 5 y creo que doy un 10, existirá una marcada brecha en cómo me veo y cómo me ven, y la diferencia en esa dialéctica hará emerger el grado de conciencia que tengo de mí y de mi grupo. Por lo tanto, si hablamos de medir Desempeño en algún campo específico (por ejemplo, Orientación al Cliente), cuando esta competencia sea total, sí o sí el grupo la podrá observar y me la devolverá como tal: pero esto sólo pasará si entrenamos nuestro nivel de Conciencia respecto a nuestro reconocimiento de la realidad.'
        );
        self::t(
            'A partir de este análisis se origina el fundamento del nombre con que bautizamos nuestra metodología: «dialéctica-proyectiva-perceptiva» y también, con el que fundamentamos nuestro Modelo Coaching por Competencias®; para ello dicha metodología: para asegurar una conciencia permanente en los integrantes de un Equipo, que están siendo observados desde distintas Competencias, como resultado de los Valores, Actitudes, Conocimientos y Habilidades (VACH) que proyectan y son responsables. Y que a partir de la capacidad de hacer foco, la misma atención hace elevar continuamente el desempeño.'
        );

        self::$section->addTitle('Escala', 2);
        self::t(
            'Para medirlo, utilizamos una escala de 0 a 4, donde 0 indica Nunca y 4 siempre. Para poder interpretar los resultados, consideramos que un grado de consenso será Alto, o una Fortaleza, hablando en términos de competencias, cuando, en promedio, se obtengan valores iguales o superiores a 3. Consideraremos un grado de Consenso Bajo, o una Debilidad, cuando los valores obtenidos sean inferiores o iguales a 2.'
        );
    }

    private static function addTeamAnalisys()
    {
        self::$section->addPageBreak();
        self::i('@app/web/images/brands/05-team.png');

        self::addTeamRelations();
        self::addTeamEffectiveness();
        self::addTeamPerformance();
        self::addTeamCompetences();
        self::addTeamEmergents();
    }

    private static function addTeamRelations()
    {
        self::$section->addPageBreak();
        self::$section->addTitle(Yii::t('dashboard', 'Relation Matrix'), 1);

        $organizationalRelationsMatrix = Wheel::getRelationsMatrix(
            self::$team->id,
            Wheel::TYPE_ORGANIZATIONAL
        );

        if (self::$team->teamType->level_1_enabled) {
            $groupRelationsMatrix = Wheel::getRelationsMatrix(
                self::$team->id,
                Wheel::TYPE_GROUP
            );
            self::$section->addTitle(
                Yii::t('dashboard', 'Group Relations Matrix'),
                2
            );
            self::addRelationTable($groupRelationsMatrix);
        }
        if (self::$team->teamType->level_2_enabled) {
            self::$section->addTitle(
                Yii::t('dashboard', 'Organizational Relations Matrix'),
                2
            );
            self::addRelationTable($organizationalRelationsMatrix);
        }

        Html::addHtml(self::$section, self::$team->report->relations);
    }

    private static function addRelationTable($data)
    {
        $cell_font = ['size' => 8];

        $cell_paragraph = [
            'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::LEFT,
            'space' => ['before' => 0, 'after' => 0],
            'indentation' => ['firstLine' => 0],
        ];

        $table = self::$section->addTable([
            'borderSize' => 1,
            'borderColor' => '000000',
        ]);

        // Add header

        $table->addRow();
        $table->addCell()->addText('', $cell_font, $cell_paragraph);
        foreach (self::$team->activeMemberList as $id => $name) {
            $table
                ->addCell(600, self::$cell_style)
                ->addText($name, $cell_font, $cell_paragraph);
        }
        $table
            ->addCell(600, self::$cell_style)
            ->addText(Yii::t('app', 'Critical'), $cell_font, $cell_paragraph);

        // Add values

        $observed_sum = [];
        foreach (self::$team->activeMemberList as $observerId => $observer) {
            $observer_sum = 0;
            $observer_count = 0;

            $table->addRow();

            $table
                ->addCell(600, self::$cell_style)
                ->addText($observer, $cell_font, $cell_paragraph);

            foreach (
                self::$team->activeMemberList
                as $observedId => $observed
            ) {
                foreach ($data as $datum) {
                    if (
                        $datum['observer_id'] == $observerId &&
                        $datum['observed_id'] == $observedId
                    ) {
                        if (
                            $datum['value'] >
                            Yii::$app->params['good_consciousness']
                        ) {
                            $class = self::$good_cell;
                        } elseif (
                            $datum['value'] <
                            Yii::$app->params['minimal_consciousness']
                        ) {
                            $class = self::$bad_cell;
                        } else {
                            $class = self::$regular_cell;
                        }

                        $value = round(($datum['value'] * 100) / 4, 1) . '%';

                        $table
                            ->addCell(600, $class)
                            ->addText($value, $cell_font, $cell_paragraph);

                        if ($observedId != $observerId) {
                            $observer_sum += $datum['value'];
                            $observer_count++;
                            if (!isset($observed_sum[$observedId])) {
                                $observed_sum[$observedId] = 0;
                            }
                            $observed_sum[$observedId] += $datum['value'];
                        }
                    }
                }
            }

            if ($observer_count > 0) {
                if (
                    $observer_sum / $observer_count >
                    Yii::$app->params['good_consciousness']
                ) {
                    $class = self::$good_cell;
                } elseif (
                    $datum['value'] < Yii::$app->params['minimal_consciousness']
                ) {
                    $class = self::$bad_cell;
                } else {
                    $class = self::$regular_cell;
                }

                $value =
                    round((($observer_sum / $observer_count) * 100) / 4, 1) .
                    '%';
                $table
                    ->addCell(600, $class)
                    ->addText($value, $cell_font, $cell_paragraph);
            }
        }

        // Add footer

        $table->addRow();
        $table
            ->addCell()
            ->addText(
                Yii::t('dashboard', 'M. Productivity'),
                $cell_font,
                $cell_paragraph
            );
        if ($observer_count > 0) {
            foreach (self::$team->activeMemberList as $id => $member) {
                if ($id == 0) {
                    continue;
                }
                $sum = $observed_sum[$id];
                if (
                    $sum / $observer_count >
                    Yii::$app->params['good_consciousness']
                ) {
                    $class = self::$good_cell;
                } elseif (
                    $sum / $observer_count <
                    Yii::$app->params['minimal_consciousness']
                ) {
                    $class = self::$bad_cell;
                } else {
                    $class = self::$regular_cell;
                }

                $value = round((($sum / $observer_count) * 100) / 4, 1) . '%';
                $table
                    ->addCell(600, $class)
                    ->addText($value, $cell_font, $cell_paragraph);
            }
            $table->addCell()->addText('', $cell_font, $cell_paragraph);
        }
    }

    private static function addTeamEffectiveness()
    {
        self::$section->addPageBreak();
        self::$section->addTitle(Yii::t('report', 'Effectiveness Matrix'), 1);

        if (self::$team->teamType->level_1_enabled) {
            $data = Wheel::getProdConsMatrix(
                self::$team->id,
                Wheel::TYPE_GROUP
            );
            self::$section->addTitle(
                Yii::t(
                    'dashboard',
                    'Group Consciousness and Responsability Matrix'
                ),
                2
            );
            self::addEffectivenessTable($data);
        }
        if (self::$team->teamType->level_2_enabled) {
            $data = Wheel::getProdConsMatrix(
                self::$team->id,
                Wheel::TYPE_ORGANIZATIONAL
            );
            self::$section->addTitle(
                Yii::t(
                    'dashboard',
                    'Organizational Consciousness and Responsability Matrix'
                ),
                2
            );
            self::addEffectivenessTable($data);
        }

        \PhpOffice\PhpWord\Shared\Html::addHtml(
            self::$section,
            self::$team->report->effectiveness
        );
    }

    private static function addEffectivenessTable($effectivenessData)
    {
        $rowsData = [];
        $members = self::$team->activeMemberList;
        foreach ($members as $index => $name) {
            foreach ($effectivenessData as $row) {
                if ($index == $row['id']) {
                    $rowsData[] = $row;
                }
            }
        }

        if (count($rowsData) == 0) {
            return;
        }

        $cell_font = ['size' => 9];

        $cell_paragraph = [
            'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::LEFT,
            'space' => ['before' => 0, 'after' => 0],
            'indentation' => ['firstLine' => 0],
        ];

        // Prepare numbers
        $sumConsciousness = 0;
        $sumProductivity = 0;

        foreach ($rowsData as $data) {
            $sumConsciousness += abs($data['consciousness']);
            $sumProductivity += $data['productivity'];
        }

        $avgConsciousness = $sumConsciousness / count($rowsData);
        $avgProductivity = $sumProductivity / count($rowsData);

        $standar_deviation = Utils::standard_deviation(
            ArrayHelper::getColumn($rowsData, 'consciousness')
        );
        $productivityDelta = Utils::variance(
            ArrayHelper::getColumn($rowsData, 'productivity')
        );

        $table = self::$section->addTable([
            'borderSize' => 1,
            'borderColor' => '000000',
        ]);

        // Add header

        $table->addRow();

        $table
            ->addCell(600, self::$cell_style)
            ->addText(
                Yii::t('app', 'Description'),
                $cell_font,
                $cell_paragraph
            );
        foreach (self::$team->activeMemberList as $id => $name) {
            $table
                ->addCell(600, self::$cell_style)
                ->addText($name, $cell_font, $cell_paragraph);
        }

        // How I see me
        $table->addRow();
        $table
            ->addCell(600, self::$cell_style)
            ->addText(
                Yii::t('dashboard', 'How I see me'),
                $cell_font,
                $cell_paragraph
            );
        foreach ($rowsData as $data) {
            $table
                ->addCell(600, self::$cell_style)
                ->addText(
                    round(($data['steem'] * 4) / 100, 2),
                    $cell_font,
                    $cell_paragraph
                );
        }

        // How they see me
        $table->addRow();
        $table
            ->addCell(600, self::$cell_style)
            ->addText(
                Yii::t('dashboard', 'How they see me'),
                $cell_font,
                $cell_paragraph
            );
        foreach ($rowsData as $data) {
            $table
                ->addCell(600, self::$cell_style)
                ->addText(
                    round(($data['productivity'] * 4) / 100, 2),
                    $cell_font,
                    $cell_paragraph
                );
        }

        // Monofactorial productivity
        $table->addRow();
        $table
            ->addCell(600, self::$cell_style)
            ->addText(
                Yii::t('dashboard', 'Monofactorial productivity'),
                $cell_font,
                $cell_paragraph
            );
        foreach ($rowsData as $data) {
            $table
                ->addCell(600, self::$cell_style)
                ->addText(
                    round($data['productivity'], 1) . '%',
                    $cell_font,
                    $cell_paragraph
                );
        }

        // Responsability
        $table->addRow();
        $table
            ->addCell(600, self::$cell_style)
            ->addText(
                Yii::t('dashboard', 'Responsability'),
                $cell_font,
                $cell_paragraph
            );
        foreach ($rowsData as $data) {
            $value = Utils::productivityText(
                $data['productivity'],
                $avgProductivity,
                $productivityDelta,
                2
            );

            if ($data['productivity'] < $avgProductivity) {
                $class = self::$regular_cell;
            } else {
                $class = self::$good_cell;
            }

            $table
                ->addCell(600, $class)
                ->addText($value, $cell_font, $cell_paragraph);
        }

        // Avg
        $table->addRow();
        $table
            ->addCell(600, self::$cell_style)
            ->addText(
                Yii::t('dashboard', 'Avg. mon. prod.'),
                $cell_font,
                $cell_paragraph
            );
        $table
            ->addCell(600, self::$cell_style)
            ->addText(
                round($avgProductivity, 1) . '%',
                $cell_font,
                $cell_paragraph
            );
        $table
            ->addCell(600, self::$cell_style)
            ->addText(
                Yii::t('dashboard', 'Prod. deviation'),
                $cell_font,
                $cell_paragraph
            );
        $table
            ->addCell(600, self::$cell_style)
            ->addText(
                round($productivityDelta, 1) . '%',
                $cell_font,
                $cell_paragraph
            );

        // Consciousness
        $table->addRow();
        $table
            ->addCell(600, self::$cell_style)
            ->addText(
                Yii::t('dashboard', 'Cons. gap'),
                $cell_font,
                $cell_paragraph
            );
        foreach ($rowsData as $data) {
            $table
                ->addCell(600, self::$cell_style)
                ->addText(
                    round(abs($data['consciousness']), 1) . '%',
                    $cell_font,
                    $cell_paragraph
                );
        }

        $table->addRow();
        $table
            ->addCell(600, self::$cell_style)
            ->addText(
                Yii::t('dashboard', 'Consciousness'),
                $cell_font,
                $cell_paragraph
            );
        foreach ($rowsData as $data) {
            $value =
                abs($data['consciousness']) > $avgConsciousness
                    ? Yii::t('app', 'Low')
                    : Yii::t('app', 'High');

            if (abs($data['consciousness']) > $avgConsciousness) {
                $class = self::$regular_cell;
            } else {
                $class = self::$good_cell;
            }

            $table
                ->addCell(600, $class)
                ->addText($value, $cell_font, $cell_paragraph);
        }

        $table->addRow();
        $table
            ->addCell(600, self::$cell_style)
            ->addText(
                Yii::t('dashboard', 'Avg. conc. gap'),
                $cell_font,
                $cell_paragraph
            );
        $table
            ->addCell(600, self::$cell_style)
            ->addText(
                round($avgConsciousness, 1) . '%',
                $cell_font,
                $cell_paragraph
            );
    }

    private static function addTeamPerformance()
    {
        self::$section->addPageBreak();
        self::$section->addTitle(Yii::t('report', 'Performance Matrix'), 1);

        if (self::$team->teamType->level_1_enabled) {
            $path = Downloader::download(
                \yii\helpers\Url::toRoute(
                    [
                        '/graph/performance',
                        'teamId' => self::$team->id,
                        'memberId' => 0,
                        'wheelType' => Wheel::TYPE_GROUP,
                    ],
                    true
                )
            );
            self::$paths[] = $path;
            self::i($path);
        }
        if (self::$team->teamType->level_2_enabled) {
            $path = Downloader::download(
                \yii\helpers\Url::toRoute(
                    [
                        '/graph/performance',
                        'teamId' => self::$team->id,
                        'memberId' => 0,
                        'wheelType' => Wheel::TYPE_ORGANIZATIONAL,
                    ],
                    true
                )
            );
            self::$paths[] = $path;
            self::i($path);
        }

        \PhpOffice\PhpWord\Shared\Html::addHtml(
            self::$section,
            self::$team->report->performance
        );
    }

    private static function addTeamCompetences()
    {
        self::$section->addPageBreak();
        self::$section->addTitle(Yii::t('report', 'Competences Matrix'), 1);

        if (self::$team->teamType->level_1_enabled) {
            $path = Downloader::download(
                \yii\helpers\Url::toRoute(
                    [
                        '/graph/competences',
                        'teamId' => self::$team->id,
                        'memberId' => 0,
                        'wheelType' => Wheel::TYPE_GROUP,
                    ],
                    true
                )
            );
            self::$paths[] = $path;
            self::i($path);
        }
        if (self::$team->teamType->level_2_enabled) {
            $path = Downloader::download(
                \yii\helpers\Url::toRoute(
                    [
                        '/graph/competences',
                        'teamId' => self::$team->id,
                        'memberId' => 0,
                        'wheelType' => Wheel::TYPE_ORGANIZATIONAL,
                    ],
                    true
                )
            );
            self::$paths[] = $path;
            self::i($path);
        }

        \PhpOffice\PhpWord\Shared\Html::addHtml(
            self::$section,
            self::$team->report->competences
        );
    }

    private static function addTeamEmergents()
    {
        self::$section->addPageBreak();
        self::$section->addTitle(Yii::t('report', 'Emergents Matrix'), 1);

        if (self::$team->teamType->level_1_enabled) {
            $path = Downloader::download(
                \yii\helpers\Url::toRoute(
                    [
                        '/graph/emergents',
                        'teamId' => self::$team->id,
                        'memberId' => 0,
                        'wheelType' => Wheel::TYPE_GROUP,
                    ],
                    true
                )
            );
            self::$paths[] = $path;
            self::i($path);
        }
        if (self::$team->teamType->level_2_enabled) {
            $path = Downloader::download(
                \yii\helpers\Url::toRoute(
                    [
                        '/graph/emergents',
                        'teamId' => self::$team->id,
                        'memberId' => 0,
                        'wheelType' => Wheel::TYPE_ORGANIZATIONAL,
                    ],
                    true
                )
            );
            self::$paths[] = $path;
            self::i($path);
        }

        \PhpOffice\PhpWord\Shared\Html::addHtml(
            self::$section,
            self::$team->report->emergents
        );
    }

    private static function addIndividualAnalisys()
    {
        self::$section->addPageBreak();
        self::i('@app/web/images/brands/06-individuals.png');

        foreach (self::$team->report->individualReports as $individualReport) {
            if ($individualReport->teamMember->active) {
                self::$section->addPageBreak();
                self::$section->addTitle(
                    $individualReport->member->fullname,
                    1
                );

                self::addIndividualPerception($individualReport);
                self::addIndividualCompetences($individualReport);
                self::addIndividualEmergents($individualReport);
                self::addIndividualRelations($individualReport);
                self::addIndividualPerformance($individualReport);
            }
        }
    }

    private static function addIndividualPerception($individualReport)
    {
        self::$section->addTitle(Yii::t('report', 'Perception Matrix'), 2);

        if (self::$team->teamType->level_1_enabled) {
            $path = Downloader::download(
                \yii\helpers\Url::toRoute(
                    [
                        '/graph/perceptions',
                        'teamId' => self::$team->id,
                        'memberId' => $individualReport->member->id,
                        'wheelType' => Wheel::TYPE_GROUP,
                    ],
                    true
                )
            );
            self::$paths[] = $path;
            self::i($path);
        }
        if (self::$team->teamType->level_2_enabled) {
            $path = Downloader::download(
                \yii\helpers\Url::toRoute(
                    [
                        '/graph/perceptions',
                        'teamId' => self::$team->id,
                        'memberId' => $individualReport->member->id,
                        'wheelType' => Wheel::TYPE_ORGANIZATIONAL,
                    ],
                    true
                )
            );
            self::$paths[] = $path;
            self::i($path);
        }

        \PhpOffice\PhpWord\Shared\Html::addHtml(
            self::$section,
            $individualReport->perception
        );
    }

    private static function addIndividualCompetences($individualReport)
    {
        self::$section->addPageBreak();
        self::$section->addTitle(Yii::t('report', 'Competences Matrix'), 2);

        if (self::$team->teamType->level_1_enabled) {
            $path = Downloader::download(
                \yii\helpers\Url::toRoute(
                    [
                        '/graph/competences',
                        'teamId' => self::$team->id,
                        'memberId' => $individualReport->member->id,
                        'wheelType' => Wheel::TYPE_GROUP,
                    ],
                    true
                )
            );
            self::$paths[] = $path;
            self::i($path);
        }
        if (self::$team->teamType->level_2_enabled) {
            $path = Downloader::download(
                \yii\helpers\Url::toRoute(
                    [
                        '/graph/competences',
                        'teamId' => self::$team->id,
                        'memberId' => $individualReport->member->id,
                        'wheelType' => Wheel::TYPE_ORGANIZATIONAL,
                    ],
                    true
                )
            );
            self::$paths[] = $path;
            self::i($path);
        }

        \PhpOffice\PhpWord\Shared\Html::addHtml(
            self::$section,
            $individualReport->competences
        );
    }

    private static function addIndividualEmergents($individualReport)
    {
        self::$section->addPageBreak();
        self::$section->addTitle(Yii::t('report', 'Emergents Matrix'), 2);

        if (self::$team->teamType->level_1_enabled) {
            $path = Downloader::download(
                \yii\helpers\Url::toRoute(
                    [
                        '/graph/emergents',
                        'teamId' => self::$team->id,
                        'memberId' => $individualReport->member->id,
                        'wheelType' => Wheel::TYPE_GROUP,
                    ],
                    true
                )
            );
            self::$paths[] = $path;
            self::i($path);
        }
        if (self::$team->teamType->level_2_enabled) {
            $path = Downloader::download(
                \yii\helpers\Url::toRoute(
                    [
                        '/graph/emergents',
                        'teamId' => self::$team->id,
                        'memberId' => $individualReport->member->id,
                        'wheelType' => Wheel::TYPE_ORGANIZATIONAL,
                    ],
                    true
                )
            );
            self::$paths[] = $path;
            self::i($path);
        }

        \PhpOffice\PhpWord\Shared\Html::addHtml(
            self::$section,
            $individualReport->emergents
        );
    }

    private static function addIndividualRelations($individualReport)
    {
        self::$section->addPageBreak();
        self::$section->addTitle(Yii::t('dashboard', 'Relation Matrix'), 2);

        if (self::$team->teamType->level_1_enabled) {
            $path = Downloader::download(
                \yii\helpers\Url::toRoute(
                    [
                        '/graph/relations',
                        'teamId' => self::$team->id,
                        'memberId' => $individualReport->member->id,
                        'wheelType' => Wheel::TYPE_GROUP,
                    ],
                    true
                )
            );
            self::$paths[] = $path;
            self::i($path);
        }
        if (self::$team->teamType->level_2_enabled) {
            $path = Downloader::download(
                \yii\helpers\Url::toRoute(
                    [
                        '/graph/relations',
                        'teamId' => self::$team->id,
                        'memberId' => $individualReport->member->id,
                        'wheelType' => Wheel::TYPE_ORGANIZATIONAL,
                    ],
                    true
                )
            );
            self::$paths[] = $path;
            self::i($path);
        }

        \PhpOffice\PhpWord\Shared\Html::addHtml(
            self::$section,
            $individualReport->relations
        );
    }

    private static function addIndividualPerformance($individualReport)
    {
        self::$section->addPageBreak();
        self::$section->addTitle(Yii::t('report', 'Performance Matrix'), 2);

        if (self::$team->teamType->level_1_enabled) {
            $path = Downloader::download(
                \yii\helpers\Url::toRoute(
                    [
                        '/graph/performance',
                        'teamId' => self::$team->id,
                        'memberId' => $individualReport->member->id,
                        'wheelType' => Wheel::TYPE_GROUP,
                    ],
                    true
                )
            );
            self::$paths[] = $path;
            self::i($path);
        }
        if (self::$team->teamType->level_2_enabled) {
            $path = Downloader::download(
                \yii\helpers\Url::toRoute(
                    [
                        '/graph/performance',
                        'teamId' => self::$team->id,
                        'memberId' => $individualReport->member->id,
                        'wheelType' => Wheel::TYPE_ORGANIZATIONAL,
                    ],
                    true
                )
            );
            self::$paths[] = $path;
            self::i($path);
        }

        \PhpOffice\PhpWord\Shared\Html::addHtml(
            self::$section,
            $individualReport->performance
        );
    }

    private static function addSummary()
    {
        self::$section->addPageBreak();
        self::i('@app/web/images/brands/07-summary.png');
        self::$section->addPageBreak();

        self::$section->addTitle(Yii::t('report', 'Summary Stage 1'), 1);
        \PhpOffice\PhpWord\Shared\Html::addHtml(
            self::$section,
            self::$team->report->summary
        );
    }

    private static function addActionPlan()
    {
        self::$section->addPageBreak();
        self::i('@app/web/images/brands/08-plan.png');
        self::$section->addPageBreak();

        self::$section->addTitle(
            Yii::t('report', 'Summary Stage 2 and Conclusions'),
            1
        );
        \PhpOffice\PhpWord\Shared\Html::addHtml(
            self::$section,
            self::$team->report->action_plan
        );
    }
}
