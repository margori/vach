SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

CREATE DATABASE IF NOT EXISTS `vach_test` DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;
USE `vach_test`;

CREATE TABLE `company` (
  `id` int(11) NOT NULL,
  `coach_id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  `notes` varchar(1000) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `company` (`id`, `coach_id`, `name`, `email`, `phone`, `created_at`, `updated_at`, `notes`) VALUES
(1, 2, 'ACME', 'acme@c.com', '(123)4567890', 1492196895, 1492196895, NULL),
(2, 2, 'Yotsuba', 'info@yotsuba.com.jp', '(11)2323423423', 1492320953, 1492320953, NULL);

CREATE TABLE `currency` (
  `id` int(11) NOT NULL,
  `from_currency` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `to_currency` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `rate` decimal(10,4) NOT NULL,
  `stamp` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `currency` (`id`, `from_currency`, `to_currency`, `rate`, `stamp`) VALUES
(1, 'ARS', 'USD', '71.7800', '2020-07-22 22:31:39');

CREATE TABLE `feedback` (
  `id` int(11) NOT NULL,
  `ip` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `effectiveness` tinyint(4) NOT NULL,
  `efficience` tinyint(4) NOT NULL,
  `satisfaction` tinyint(4) NOT NULL,
  `comment` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `datetime` datetime NOT NULL,
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `individual_report` (
  `id` int(11) NOT NULL,
  `report_id` int(11) NOT NULL,
  `person_id` int(11) NOT NULL,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  `perception_id` int(11) DEFAULT NULL,
  `relations_id` int(11) DEFAULT NULL,
  `competences_id` int(11) DEFAULT NULL,
  `emergents_id` int(11) DEFAULT NULL,
  `performance_id` int(11) DEFAULT NULL,
  `perception_keywords_id` int(11) DEFAULT NULL,
  `relations_keywords_id` int(11) DEFAULT NULL,
  `competences_keywords_id` int(11) DEFAULT NULL,
  `emergents_keywords_id` int(11) DEFAULT NULL,
  `performance_keywords_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `liquidation` (
  `id` int(11) NOT NULL,
  `stamp` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `currency` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `raw_amount` decimal(10,4) NOT NULL,
  `commision` decimal(10,4) NOT NULL,
  `net_amount` decimal(10,4) NOT NULL,
  `part1_amount` decimal(10,4) NOT NULL,
  `part2_amount` decimal(10,4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `log` (
  `id` int(11) NOT NULL,
  `coach_id` int(11) DEFAULT NULL,
  `text` text COLLATE utf8_unicode_ci,
  `datetime` datetime DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `log` (`id`, `coach_id`, `text`, `datetime`, `created_at`, `updated_at`) VALUES
(1, 1, 'Data obtained from BCRA', '2020-07-22 22:31:39', 1595457099, 1595457099),
(2, 1, 'USD/ARS rate: 71.78', '2020-07-22 22:31:39', 1595457099, 1595457099),
(3, 1, 'Currency saved', '2020-07-22 22:31:39', 1595457099, 1595457099),
(4, 1, '20 Team Assessment Licence ha sido exitosamente creado.', '2020-07-22 22:31:39', 1595457099, 1595457099);

CREATE TABLE `migration` (
  `version` varchar(180) COLLATE utf8_unicode_ci NOT NULL,
  `apply_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `migration` (`version`, `apply_time`) VALUES
('m000000_000000_base', 1492102011),
('m150501_231226_add_user_tables', 1492102019),
('m150502_025947_add_wheel_tables', 1492102021),
('m150523_032250_answer_scale_to_0_to_4', 1492102021),
('m150527_035800_add_wheel_questions_table', 1492102215),
('m150528_041349_change_wheel_user_to_coachee', 1492102216),
('m150602_233124_add_goal_tables', 1492102217),
('m150606_160839_add_administrator', 1492102217),
('m150606_194443_add_company', 1492102220),
('m150609_001157_add_user_phone', 1492102220),
('m150609_053234_add_team_tables', 1492102226),
('m150611_044001_add_assessment_tables', 1492102227),
('m150613_021630_add_assessment_wheel_fields', 1492102234),
('m150616_235204_add_group_and_organizational_questions', 1492102383),
('m150617_015144_add_dimension_to_answers', 1492102383),
('m150623_031725_decrease_question_orders', 1492102383),
('m150624_025741_dimension_to_tinyint', 1492102384),
('m150704_015414_add_autofill_answers_field', 1492102385),
('m150721_023053_add_feedback_table', 1492102386),
('m150723_223230_add_user_to_feedback', 1492102386),
('m150724_065744_add_assessment_name', 1492102387),
('m150724_070020_delete_unused_tables_and_fields', 1492102388),
('m150725_000559_add_technical_report', 1492102392),
('m150725_224449_add_question_index', 1492102392),
('m150920_183258_add_event_log_table', 1492102394),
('m151114_012747_add_relation_analisys', 1492102394),
('m151114_013636_add_individual_performance_analisys', 1492102395),
('m151216_041047_team_blocked_field', 1492102396),
('m160614_041645_person_gender', 1492102397),
('m160618_191226_assessment_version', 1492102397),
('m160701_033528_add_person', 1492102398),
('m160701_033529_add_company', 1492102400),
('m160701_034038_fill_person', 1492102400),
('m160701_034039_fill_company', 1492102400),
('m160701_040351_move_fk_to_person', 1492102405),
('m160701_040352_move_fk_to_company', 1492102407),
('m160701_045821_clean_user_table', 1492102410),
('m170215_042851_add_active_to_person', 1492102411),
('m170227_044825_add_product_and_stock', 1492102414),
('m170228_044825_add_payment', 1492102417),
('m170309_044329_null_in_reports', 1492102425),
('m170325_050124_add_question_table', 1492102429),
('m170325_053850_drop_anwser_type_field', 1492102430),
('m170326_043036_bind_answer_to_question', 1492102431),
('m170326_192255_add_creator_to_stock', 1492102433),
('m170403_030521_question_fk', 1492102435),
('m170403_040521_creator_fk', 1492102437),
('m170404_030521_add_assessment_coach', 1492102440),
('m170424_034317_wheel_status_and_sent_count', 1493091866),
('m170426_005337_sanitize_reports', 1493269319),
('m170426_005628_drop_individual_summary', 1493269319),
('m170427_042955_add_declined_payment_status', 1493269319),
('m170428_033049_add_payment_commision_fields', 1494465575),
('m170429_215526_add_currency_rates', 1494465575),
('m170429_223113_add_manual_payment_field', 1494465575),
('m170504_224630_add_payment_liquidation', 1494465575),
('m170511_002445_add_session_token', 1494465575),
('m170515_230603_add_person_shortname', 1494909385),
('m170516_022644_add_report_keywords', 1494909385),
('m170518_060529_add_stock_assessment_relation', 1495415609),
('m170519_011035_mark_pending_payments', 1495415609),
('m170520_000204_drop_assessment', 1495300680),
('m170520_185059_add_missing_fk', 1500759815),
('m170719_025000_new_stock_table', 1500759815),
('m170722_182046_fix_missing_payment_uuid', 1500759815),
('m170722_210647_fix_stock_status', 1500759815),
('m170818_033118_add_swap_competences', 1525752166),
('m170820_204226_add_team_type_tables', 1525752166),
('m170923_052805_add_person_photo', 1525752166),
('m180123_043834_split_report_fields', 1525752167),
('m180123_045134_populate_report_fields', 1525752167),
('m180123_051733_drop_report_texts', 1525752167),
('m180327_234428_add_unique_username', 1525752167),
('m180825_004150_add_status_payment_log', 1541182936),
('m180825_004151_add_transactions', 1541182936),
('m180825_004152_populate_transactions', 1541182936),
('m180825_220142_drop_unused_field_payment', 1541182936),
('m180826_233158_drop_init_payment_status', 1541182936),
('m180827_024140_add_payment_log_creator', 1541182936),
('m180827_030448_add_fk_payment_creator', 1541183063),
('m180829_234218_add_transactions_fk', 1541183063),
('m180929_200905_drop_init_transaction_status', 1541183063),
('m181018_004525_add_notes', 1541183063),
('m181130_000632_add_custom_wheel_type', 1543889493),
('m181201_131301_add_team_type_dimension', 1543889493),
('m181201_132648_populate_team_type_dimension', 1543889493),
('m181201_170004_add_team_type_dimension_sort', 1543889493),
('m181210_014533_add_team_type_dimension_description', 1544489549),
('m181210_014838_populate_team_type_dimension_description', 1544489549),
('m210731_175938_team_type_enabled_field', 1627778059);

CREATE TABLE `payment` (
  `id` int(11) NOT NULL,
  `uuid` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `coach_id` int(11) NOT NULL,
  `concept` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `status` enum('init','pending','paid','rejected','error') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'init',
  `stamp` datetime NOT NULL,
  `creator_id` int(11) NOT NULL,
  `currency` varchar(3) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'USD',
  `rate` decimal(10,2) DEFAULT NULL,
  `commision` decimal(10,2) DEFAULT NULL,
  `commision_currency` varchar(3) COLLATE utf8_unicode_ci DEFAULT NULL,
  `is_manual` tinyint(1) NOT NULL DEFAULT '0',
  `part_distribution` int(11) NOT NULL DEFAULT '50',
  `liquidation_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `payment` (`id`, `uuid`, `coach_id`, `concept`, `amount`, `status`, `stamp`, `creator_id`, `currency`, `rate`, `commision`, `commision_currency`, `is_manual`, `part_distribution`, `liquidation_id`) VALUES
(1, '5f18be4b42dd70.81849135', 2, '20 Team Assessment Licence', '360.00', 'paid', '2020-07-22 22:31:39', 1, 'USD', '71.78', '0.00', 'ARS', 1, 50, NULL);

CREATE TABLE `payment_log` (
  `id` int(11) NOT NULL,
  `payment_id` int(11) NOT NULL,
  `status` enum('pending','paid','rejected','error') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'pending',
  `external_id` text COLLATE utf8_unicode_ci,
  `external_data` text COLLATE utf8_unicode_ci,
  `stamp` datetime NOT NULL,
  `creator_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `payment_log` (`id`, `payment_id`, `status`, `external_id`, `external_data`, `stamp`, `creator_id`) VALUES
(1, 1, 'paid', NULL, NULL, '2020-07-22 22:31:39', 1);

CREATE TABLE `person` (
  `id` int(11) NOT NULL,
  `coach_id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `surname` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `gender` int(11) NOT NULL DEFAULT '0',
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  `shortname` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `photo` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `notes` varchar(1000) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `person` (`id`, `coach_id`, `name`, `surname`, `email`, `phone`, `gender`, `created_at`, `updated_at`, `shortname`, `photo`, `notes`) VALUES
(1, 2, 'Ariel', 'A', 'ariel@a.com', '(123)12345678', 2, 1492196613, 1492196994, 'Ariel A', NULL, NULL),
(2, 2, 'Beatriz', 'B', 'beatriz@b.com', '(234)12345678', 1, 1492196616, 1492196954, 'Beatriz B', NULL, NULL),
(3, 2, 'Carlos', 'C', 'carlos@c.com', '(345)12345678', 0, 1492196619, 1492196987, 'Carlos C', NULL, NULL),
(4, 2, 'Patricio', 'P', 'patricio@p.com', '(456)12345678', 0, 1492197048, 1492197048, 'Patricio P', NULL, NULL),
(5, 2, 'Dallas', 'D', 'dallas@d.com', '(567)12345678', 2, 1492321092, 1492321092, 'Dallas D', NULL, NULL),
(6, 2, 'Esteban', 'E', 'esteben@e.com', '(678)12345678', 0, 1492321137, 1492321137, 'Esteban E', NULL, NULL),
(7, 2, 'Fernanda', 'F', 'fernanda@f.com', '(789)12345678', 1, 1492321158, 1492321158, 'Fernanda F', NULL, NULL),
(8, 2, 'Quinn', 'Q', 'quinn@q.com', '(890)12345678', 1, 1492321269, 1492321269, 'Quinn Q', NULL, NULL);

CREATE TABLE `product` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `price` decimal(10,2) NOT NULL COMMENT 'USD',
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `product` (`id`, `name`, `description`, `price`, `created_at`, `updated_at`) VALUES
(1, 'Team Assessment Licence', 'Team Assessment Licence', '18.00', 1492102411, 1492102411);

CREATE TABLE `question` (
  `id` int(11) NOT NULL,
  `text` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `question` (`id`, `text`) VALUES
(1, '¿Me divierto y participo del ocio?'),
(2, '¿Tengo claro cuáles son mis momentos de ocio?'),
(3, '¿Logro separar mi familia del ocio?'),
(4, '¿Logro separar mi trabajo del ocio?'),
(5, '¿Desarrollo personal?'),
(6, '¿Identifico un hobby en especial?'),
(7, '¿Tengo actividades de ocio programadas?'),
(8, '¿Tengo actividades de ocio compartidas?'),
(9, '¿Me estimula el juntarme con amigos?'),
(10, '¿Qué interés tenés en trabajar esta dimensión?'),
(11, '¿Ambiente y entorno de trabajo?'),
(12, '¿Se valoran mis capacidades?'),
(13, '¿Mi trabajo es acorde a mi vocación?'),
(14, '¿Tengo un trabajo estimulante?'),
(15, '¿Tengo posibilidad de crecimiento?'),
(16, '¿Tengo claridad con mis Proyectos de trabajo?'),
(17, '¿Controlo el estrés?'),
(18, '¿Tengo un presupuesto estable?'),
(19, '¿Estado de satisfacción en gral con mi trabajo?'),
(20, '¿Disponibilidad para la familia?'),
(21, '¿Relación con los padres?'),
(22, '¿Relación de pareja?'),
(23, '¿Relación con los hijos?'),
(24, '¿Relación con otros miembros de la familia?'),
(25, '¿Actividades recreativas en conjunto?'),
(26, '¿Comodidades familiares?'),
(27, '¿Proyecto en común?'),
(28, '¿Nivel de satisfacción en general?'),
(29, '¿Apariencia?'),
(30, '¿Energía?'),
(31, '¿Actividad física?'),
(32, '¿Dieta equilibrada?'),
(33, '¿Estimulación de sentidos?'),
(34, '¿Ambiente y Entorno en general?'),
(35, '¿Nivel de materialización de los Proyectos?'),
(36, '¿Hábitos que no me gustan?'),
(37, '¿Estado de salud en general?'),
(38, '¿Mis estados de ánimo son estables?'),
(39, '¿Me conecto con mi pasión?'),
(40, '¿Me conecto con mi voluntad?'),
(41, '¿Mi actitud emocional es positiva?'),
(42, '¿Tengo una vida estimulante?'),
(43, '¿Cómo es mi autoestima?'),
(44, '¿Ansiedad?'),
(45, '¿Nostalgia?'),
(46, '¿Miedos?'),
(47, '¿Qué grado de felicidad tengo?'),
(48, '¿Hay alguna situación que no me deja ser feliz?'),
(49, '¿Qué tan seguido me encuentro juzgando las situaciones?'),
(50, '¿Qué tan seguido me descubro tejiendo fantasías?'),
(51, '¿Estoy esperando algo de alguien?'),
(52, '¿Espero que las personas adivinen lo que necesito?'),
(53, '¿Soy racional?'),
(54, '¿Mi actitud mental es positiva?'),
(55, '¿Tengo una mentalidad abierta?'),
(56, '¿Me considero una persona de mucha consciencia?'),
(57, '¿Me preocupo por el crecimiento de los demás?'),
(58, '¿Tengo claridad con mi vocación?'),
(59, '¿Tengo claridad en cuál es mi servicio diferencial?'),
(60, '¿Qué nivel de Escucha tengo?'),
(61, '¿Qué nivel de Comunicación tengo?'),
(62, '¿Las relaciones con mis Vínculos son sanas?'),
(63, '¿Participo en Comunidades y trabajos sociales?'),
(64, '¿Existe satisfacción con la Comunidad en que vivo?'),
(65, '¿Creo en algún tipo de configuración superior?'),
(66, '¿Soy congruente con ese tipo de configuración?'),
(67, '¿Tengo claridad con mi propósito de trascendencia?'),
(68, '¿Soy congruente en mi vida con mis Valores?'),
(69, '¿Me mantengo en un continuo con el Amor?'),
(70, '¿Me mantengo en un continuo con la Verdad?'),
(71, '¿Doy sin esperar recibir?'),
(72, '¿Me mantengo en un continuo con la Paciencia?'),
(73, '¿Tengo una rutina espiritual?'),
(74, '¿Es proactivo?'),
(75, '¿Ve las oportunidades de mejora?'),
(76, '¿Está comprometid@ con la mejora continua?'),
(77, '¿Tiene un criterio independiente?'),
(78, '¿Diseña diversos procesos/proyectos de trabajo?'),
(79, '¿Propone políticas organizacionales?'),
(80, '¿Promueve la participación de sus compañeros?'),
(81, '¿Está comprometid@ con la Iniciativa?'),
(82, '¿Se considera idóneo en su trabajo?'),
(83, '¿Habla con fundamentos?'),
(84, '¿Considera que sus intervenciones aportan al Equipo?'),
(85, '¿Es consciente del efecto de sus comportamientos?'),
(86, '¿Es consciente de su inteligencia emocional?'),
(87, '¿Es ordenado?'),
(88, '¿Se capacita?'),
(89, '¿Está comprometid@ con la Pertinencia?'),
(90, '¿Es consciente de que necesita de su Equipo para crecer?'),
(91, '¿Está satisfecho con la trayectoria de la Empresa?'),
(92, '¿Está satisfecho con la trayectoria de los integrantes del Equipo?'),
(93, '¿Siente que la Org. también es un producto suyo?'),
(94, '¿Sabe identificar los sentimientos de los demás?'),
(95, '¿Cuida los vínculos?'),
(96, '¿Toma su lugar en el Equipo?'),
(97, '¿Está comprometid@ con la Pertenencia?'),
(98, '¿Busca el equilibrio entre el bien individual y el bien común?'),
(99, '¿Colabora con sus compañeros?'),
(100, '¿Tiene un buen desempeño en el Equipo?'),
(101, '¿Respeta los tiempos de trabajo de los miembros del Equipo?'),
(102, '¿Se siente integrado al Equipo?'),
(103, '¿Considera que es importante su feedback al Equipo?'),
(104, '¿Considera que es importante el feedback que recibe de su Equipo?'),
(105, '¿Está comprometid@ con el Equipo?'),
(106, '¿Es flexible?'),
(107, '¿Transforma las debilidades en fortalezas?'),
(108, '¿Valora perspectivas distintas?'),
(109, '¿Modifica sus opiniones para poder acordar?'),
(110, '¿Se ajusta a los nuevos escenarios?'),
(111, '¿Aborda los supuestos que se instalan en el Equipo?'),
(112, '¿Hace una revisión crítica de sí mismo?'),
(113, '¿Está comprometid@ con la Flexibilidad?'),
(114, '¿Escucha profundamente?'),
(115, '¿Chequea que su mensaje sea comprendido?'),
(116, '¿Mantiene una comunicación regular con sus pares?'),
(117, '¿Es consciente que la información que gestiona llegue en tiempo y forma a destino?'),
(118, '¿Maneja alguna estrategia de control sobre la información que administra?'),
(119, '¿Tiene en cuenta el contexto al momento del diálogo?'),
(120, '¿Aporta activamente a la relación del Equipo?'),
(121, '¿Está comprometid@ con la Comunicación?'),
(122, '¿Consigue delegar responsablemente sus actividades?'),
(123, '¿Gestiona el desempeño de su gente en forma sistemática y regular?'),
(124, '¿Transmite y logra el compromiso de su gente frente a los objetivos del negocio?'),
(125, '¿Es reconocido como líder en su Equipo?'),
(126, '¿Es un ejemplo para los demás?'),
(127, '¿Cree que le da lo suficiente a su Equipo?'),
(128, '¿Le preocupa el desarrollo de su Gente?'),
(129, '¿Está comprometid@ con el Liderazgo?'),
(130, '¿Está tranquilo cuando otros lo representan?'),
(131, '¿Se fía de lo que sus pares y subordinados le cuentan?'),
(132, '¿Cree que la valoración del otro (hacia usted) es importante?'),
(133, '¿Considera importante su presencia en el Equipo?'),
(134, '¿Confían en Usted?'),
(135, '¿Reconoce las potencialidades de los demás?'),
(136, '¿Dice y actúa según lo que piensa?'),
(137, '¿Está comprometid@ con la Legitimación?'),
(138, '¿Adhiere a la creatividad por convicción?'),
(139, '¿Reconoce esta competencia como una exigencia propia del contexto del mercado?'),
(140, '¿Se considera una persona creativa?'),
(141, '¿Considera que su Entorno l@ valora como una persona proclive a la innovación?'),
(142, '¿Alienta y valoriza que otra persona del Equipo sea creativa?'),
(143, '¿Considera que la creatividad mejora el servicio?'),
(144, '¿Considera que la creatividad mejora la competitividad?'),
(145, '¿Está comprometid@ con la creatividad?'),
(146, '¿Es productiv@ en sus tareas?'),
(147, '¿Alcanza las metas y los objetivos establecidos?'),
(148, '¿Orienta su comportamiento hacia la superación constante de los objetivos planteados?'),
(149, '¿Se involucra en el pronóstico de las metas y los objetivos a alcanzar?'),
(150, '¿Establece indicadores para la mejora continua?'),
(151, '¿Hace un seguimiento regular del grado de evolución de esos indicadores?'),
(152, '¿Reconoce la importancia de hacer seguimiento y medición del avance de los resultados?'),
(153, '¿Está comprometid@ con la orientación a los resultados?'),
(154, '¿Actúa con sensibilidad ante las necesidades del cliente?'),
(155, '¿Tiene la capacidad de anticiparse ante las necesidades futuras del cliente?'),
(156, '¿Conoce la totalidad de los productos y servicios de la Org.?'),
(157, '¿Presenta propuestas y soluciones que agregan valor a sus clientes?'),
(158, '¿Tiene la capacidad de empatizar con sus clientes?'),
(159, '¿Responde al sentido de urgencia de las demandas de sus clientes?'),
(160, '¿Se preocupa por satisfacer plenamente al cliente?'),
(161, '¿Está comprometid@ con la atención al cliente?'),
(162, '¿Cuida el detalle al momento de presentar los trabajos?'),
(163, '¿Se reconoce eficiente al momento de afectar los recursos para su labor?'),
(164, '¿Respalda sus fundamentos con información verificable?'),
(165, '¿Establece estrategias de procesos?'),
(166, '¿Mide regularmente la Calidad de su trabajo?'),
(167, '¿Está atent@ a las necesidades del Equipo?'),
(168, '¿Está atent@ a las necesidades de la Org.?'),
(169, '¿Está comprometid@ con la calidad?'),
(170, '¿Adhiere a la gestión del cambio por convicción?'),
(171, '¿Entiende el cambio como una dinámica propia del Negocio?'),
(172, '¿L@ consideran una persona flexible a los cambios?'),
(173, '¿Considera que el cambio es mejora?'),
(174, '¿Tiene la capacidad de comprender y apreciar perspectivas diferentes?'),
(175, '¿Hace una revisión crítica de las estrategias y objetivos de su Área?'),
(176, '¿Alienta y valoriza los cambios?'),
(177, '¿Está comprometid@ con la gestión del cambio?'),
(178, '¿Sabe abordar y resolver conflictos?'),
(179, '¿Asume negociaciones y acuerdos?'),
(180, '¿Busca que las resoluciones sean grupales?'),
(181, '¿Intenta enseñar la lógica y los beneficios de su posición?'),
(182, '¿Intenta hacer lo que es necesario para evitar tensiones inútiles?'),
(183, '¿Tiene en cuenta todo lo que conscierne a Usted y al otro?'),
(184, '¿Propone e indaga necesidades?'),
(185, '¿Está comprometid@ con la resolución de conflictos?'),
(186, '¿Proyecta en su tarea diaria escenarios futuros?'),
(187, '¿Se considera una persona que trabaja “mirando más allá”?'),
(188, '¿Considera que sus compañeros l@ ven como una persona con visión a futuro?'),
(189, '¿Promueve la planificación?'),
(190, '¿Alienta y valoriza el análisis estratégico de la gestión que hace su Equipo?'),
(191, '¿Está de acuerdo con que la visión estratégica permite adelantarse al futuro y optimizar decisiones?'),
(192, '¿Está comprometid@ con la visión estratégica de la Org.?'),
(193, '¿Tiene presente cuál es la Visión de la Org.?'),
(194, '¿Tiene presente cuál es la Misión de la Org.?'),
(195, '¿Tiene presente cuáles son los Valores de la Org.?'),
(196, '¿Se ajustan sus acciones a las necesidades de la Org.?'),
(197, '¿Sabe lo que se esperan de Ud. en su trabajo?'),
(198, '¿Considera que su aporte es importante para la Misión de la Org.?'),
(199, '¿Reconoce los canales regulares de toma de decisiones?'),
(200, '¿Está comprometid@ con la identidad?');

CREATE TABLE `report` (
  `id` int(11) NOT NULL,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  `team_id` int(11) DEFAULT NULL,
  `introduction_id` int(11) DEFAULT NULL,
  `effectiveness_id` int(11) DEFAULT NULL,
  `performance_id` int(11) DEFAULT NULL,
  `competences_id` int(11) DEFAULT NULL,
  `emergents_id` int(11) DEFAULT NULL,
  `relations_id` int(11) DEFAULT NULL,
  `introduction_keywords_id` int(11) DEFAULT NULL,
  `effectiveness_keywords_id` int(11) DEFAULT NULL,
  `performance_keywords_id` int(11) DEFAULT NULL,
  `competences_keywords_id` int(11) DEFAULT NULL,
  `emergents_keywords_id` int(11) DEFAULT NULL,
  `relations_keywords_id` int(11) DEFAULT NULL,
  `action_plan_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `report_field` (
  `report_field_id` int(11) NOT NULL,
  `content` text COLLATE utf8_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `stock` (
  `id` int(11) NOT NULL,
  `coach_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `status` enum('invalid','valid','consumed','error') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'invalid',
  `created_stamp` datetime NOT NULL,
  `creator_id` int(11) NOT NULL,
  `payment_id` int(11) DEFAULT NULL,
  `consumed_stamp` datetime DEFAULT NULL,
  `consumer_id` int(11) DEFAULT NULL,
  `team_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `stock` (`id`, `coach_id`, `product_id`, `price`, `status`, `created_stamp`, `creator_id`, `payment_id`, `consumed_stamp`, `consumer_id`, `team_id`) VALUES
(1, 2, 1, '18.00', 'valid', '2020-07-22 22:31:39', 1, 1, NULL, NULL, NULL),
(2, 2, 1, '18.00', 'valid', '2020-07-22 22:31:39', 1, 1, NULL, NULL, NULL),
(3, 2, 1, '18.00', 'valid', '2020-07-22 22:31:39', 1, 1, NULL, NULL, NULL),
(4, 2, 1, '18.00', 'valid', '2020-07-22 22:31:39', 1, 1, NULL, NULL, NULL),
(5, 2, 1, '18.00', 'valid', '2020-07-22 22:31:39', 1, 1, NULL, NULL, NULL),
(6, 2, 1, '18.00', 'valid', '2020-07-22 22:31:39', 1, 1, NULL, NULL, NULL),
(7, 2, 1, '18.00', 'valid', '2020-07-22 22:31:39', 1, 1, NULL, NULL, NULL),
(8, 2, 1, '18.00', 'valid', '2020-07-22 22:31:39', 1, 1, NULL, NULL, NULL),
(9, 2, 1, '18.00', 'valid', '2020-07-22 22:31:39', 1, 1, NULL, NULL, NULL),
(10, 2, 1, '18.00', 'valid', '2020-07-22 22:31:39', 1, 1, NULL, NULL, NULL),
(11, 2, 1, '18.00', 'valid', '2020-07-22 22:31:39', 1, 1, NULL, NULL, NULL),
(12, 2, 1, '18.00', 'valid', '2020-07-22 22:31:39', 1, 1, NULL, NULL, NULL),
(13, 2, 1, '18.00', 'valid', '2020-07-22 22:31:39', 1, 1, NULL, NULL, NULL),
(14, 2, 1, '18.00', 'valid', '2020-07-22 22:31:39', 1, 1, NULL, NULL, NULL),
(15, 2, 1, '18.00', 'valid', '2020-07-22 22:31:39', 1, 1, NULL, NULL, NULL),
(16, 2, 1, '18.00', 'valid', '2020-07-22 22:31:39', 1, 1, NULL, NULL, NULL),
(17, 2, 1, '18.00', 'valid', '2020-07-22 22:31:39', 1, 1, NULL, NULL, NULL),
(18, 2, 1, '18.00', 'valid', '2020-07-22 22:31:39', 1, 1, NULL, NULL, NULL),
(19, 2, 1, '18.00', 'valid', '2020-07-22 22:31:39', 1, 1, NULL, NULL, NULL),
(20, 2, 1, '18.00', 'valid', '2020-07-22 22:31:39', 1, 1, NULL, NULL, NULL);

CREATE TABLE `team` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `sponsor_id` int(11) NOT NULL,
  `company_id` int(11) NOT NULL,
  `coach_id` int(11) NOT NULL,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  `team_type_id` int(11) NOT NULL,
  `notes` varchar(1000) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `team` (`id`, `name`, `sponsor_id`, `company_id`, `coach_id`, `created_at`, `updated_at`, `team_type_id`, `notes`) VALUES
(1, 'Núcleo Inicial', 4, 1, 2, 1492197123, 1492197123, 1, NULL),
(2, 'Núcleo Final', 4, 1, 2, 1492197137, 1492197137, 1, NULL),
(3, 'Ventas', 8, 2, 2, 1492321286, 1492321316, 1, NULL);

CREATE TABLE `team_coach` (
  `id` int(11) NOT NULL,
  `team_id` int(11) NOT NULL,
  `coach_id` int(11) NOT NULL,
  `anonimize` tinyint(1) NOT NULL,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `team_member` (
  `id` int(11) NOT NULL,
  `team_id` int(11) NOT NULL,
  `person_id` int(11) NOT NULL,
  `active` tinyint(1) NOT NULL,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `team_member` (`id`, `team_id`, `person_id`, `active`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, 1492197064, 1492197064),
(2, 1, 2, 1, 1492197067, 1492197067),
(3, 1, 3, 1, 1492197070, 1492197070),
(4, 2, 1, 1, 1492197064, 1492197064),
(5, 2, 2, 1, 1492197067, 1492197067),
(6, 2, 3, 1, 1492197070, 1492197070),
(7, 3, 5, 1, 1492321297, 1492321297),
(8, 3, 6, 1, 1492321302, 1492321302),
(9, 3, 7, 1, 1492321307, 1492321307);

CREATE TABLE `team_type` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `product_id` int(11) NOT NULL,
  `level_0_name` varchar(200) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'individual',
  `level_0_enabled` tinyint(1) NOT NULL DEFAULT '1',
  `level_1_name` varchar(200) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'grupal',
  `level_1_enabled` tinyint(1) NOT NULL DEFAULT '1',
  `level_2_name` varchar(200) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'organizacional',
  `level_2_enabled` tinyint(1) NOT NULL DEFAULT '1',
  `dimension_sort` int(11) NOT NULL DEFAULT '4',
  `enabled` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `team_type` (`id`, `name`, `product_id`, `level_0_name`, `level_0_enabled`, `level_1_name`, `level_1_enabled`, `level_2_name`, `level_2_enabled`, `dimension_sort`, `enabled`) VALUES
(1, 'Empresa', 1, 'individual', 1, 'grupal', 1, 'organizacional', 1, 4, 1),
(2, 'Áreas', 1, 'individual', 0, 'áreas', 1, 'organizacional', 0, 4, 1);

CREATE TABLE `team_type_dimension` (
  `id` int(11) NOT NULL,
  `team_type_id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `order` int(11) NOT NULL,
  `level` int(11) NOT NULL,
  `description` text COLLATE utf8_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `team_type_dimension` (`id`, `team_type_id`, `name`, `order`, `level`, `description`) VALUES
(1, 1, 'Tiempo libre', 0, 0, 'La condición neutra personal que pasa a ser compartida en los ámbitos posteriores donde el sujeto puede estar consigo mismo y conectado con su creatividad, su pasividad, etc.'),
(2, 1, 'Trabajo', 1, 0, 'Ámbito donde desempeñamos capacidades y conductas que generarían un intercambio de procesos para satisfacer las necesidades personales y del ámbito anterior. Grupo secundario.'),
(3, 1, 'Familia', 2, 0, 'Grupo vincular básico de socialización del cual tomamos las creencias y conductas principales para la adaptación social.'),
(4, 1, 'Dimensión física', 3, 0, 'Refiere a nuestro cuerpo y a aquellas cosas materializables por nosotros mismos, a acciones específicas.'),
(5, 1, 'Dimensión emocional', 4, 0, 'Es el ámbito de las relaciones o los vínculos, donde se vivencia la intención personal frente a sí mismo, a otro, a una experiencia o cosa, invitándonos a alejarnos o a acercarnos al mismo.'),
(6, 1, 'Dimensión mental', 5, 0, 'Refiere al proceso organizativo personal que invita a la reflexión o a la elección de emociones y conductas particulares frente a personas, sucesos o cosas.'),
(7, 1, 'Dimensión existencial', 6, 0, 'Refiere a la visión personal a la manera como queremos vivenciar nuestro propósito en la vida, a la manera como decidimos experimentar nuestra condición humana.'),
(8, 1, 'Dimensión espiritual', 7, 0, 'Es el ámbito de nuestra misión, de aquello que somos en la vida, con nuestro propósito o manera personal de estar.'),
(9, 1, 'Iniciativa', 0, 1, 'Habilidad para originar ideas novedosas y desarrollar acciones para su implementación.'),
(10, 1, 'Pertinencia', 1, 1, 'Grado de compromiso hacia los Valores aceptados y reconocidos por el grupo de pertenencia.'),
(11, 1, 'Pertenencia', 2, 1, 'Nivel de experticia e idoneidad reconocida por los miembros del Equipo, en la disciplina de desempeño .'),
(12, 1, 'Trabajo en equipo', 3, 1, 'Disposición para integrarse a equipos de trabajo contribuyendo al cumplimiento de los objetivos, fortaleciendo las relaciones interpersonales de sus miembros.'),
(13, 1, 'Flexibilidad', 4, 1, 'Actitud para adherir positiva y flexiblemente a cambios en el contexto y en los procesos de trabajo.'),
(14, 1, 'Comunicación', 5, 1, 'Grado y modo de interacción con pares en el contexto de trabajo.'),
(15, 1, 'Liderazgo', 6, 1, 'Capacidad para guiar a sus colaboradores hacia el logro de los objetivos, generando un alto nivel de motivación.'),
(16, 1, 'Legitimación', 7, 1, 'Nivel de aceptación y reconocimiento de las decisiones y conductas, en su contexto de trabajo.'),
(17, 1, 'Creatividad', 0, 2, 'Habilidad para originar ideas novedosas y desarrollar acciones concretas para su implementación.'),
(18, 1, 'Orientación a los resultados', 1, 2, 'Capacidad para entender, anticiparse y satisfacer las necesidades del Cliente, tanto interno como externo.'),
(19, 1, 'Orientación al cliente', 2, 2, 'Actitud constante hacia el logro y la superación de los objetivos individuales y del grupo. Incluye la preocupación por el uso adecuado de los recursos y por el incremento sostenido de la productividad.'),
(20, 1, 'Orientación a la calidad', 3, 2, 'Actitud hacia la búsqueda permanente del mejoramiento de los productos/procesos y de la excelencia.'),
(21, 1, 'Resolución de conflictos', 4, 2, 'Resolver eficazmente diferencias en distintas posiciones, modificando posturas personales y/ o grupales en beneficio de todas las partes involucradas.'),
(22, 1, 'Gestión del cambio', 5, 2, 'Capacidad para promover e implementar los cambios requeridos a fin de mantener la ventaja competitiva de la empresa en el mercado.'),
(23, 1, 'Visión estratégica', 6, 2, 'Habilidad para imaginar escenarios futuros y formular estrategias orientadas a mejorar el posicionamiento de su equipo/área dentro de la Organización.'),
(24, 1, 'Identidad', 7, 2, 'Pleno compromiso e identificación con los Valores y la Cultura de la Organización.'),
(25, 2, 'Tiempo libre', 0, 0, 'La condición neutra personal que pasa a ser compartida en los ámbitos posteriores donde el sujeto puede estar consigo mismo y conectado con su creatividad, su pasividad, etc.'),
(26, 2, 'Trabajo', 1, 0, 'Ámbito donde desempeñamos capacidades y conductas que generarían un intercambio de procesos para satisfacer las necesidades personales y del ámbito anterior. Grupo secundario.'),
(27, 2, 'Familia', 2, 0, 'Grupo vincular básico de socialización del cual tomamos las creencias y conductas principales para la adaptación social.'),
(28, 2, 'Dimensión física', 3, 0, 'Refiere a nuestro cuerpo y a aquellas cosas materializables por nosotros mismos, a acciones específicas.'),
(29, 2, 'Dimensión emocional', 4, 0, 'Es el ámbito de las relaciones o los vínculos, donde se vivencia la intención personal frente a sí mismo, a otro, a una experiencia o cosa, invitándonos a alejarnos o a acercarnos al mismo.'),
(30, 2, 'Dimensión mental', 5, 0, 'Refiere al proceso organizativo personal que invita a la reflexión o a la elección de emociones y conductas particulares frente a personas, sucesos o cosas.'),
(31, 2, 'Dimensión existencial', 6, 0, 'Refiere a la visión personal a la manera como queremos vivenciar nuestro propósito en la vida, a la manera como decidimos experimentar nuestra condición humana.'),
(32, 2, 'Dimensión espiritual', 7, 0, 'Es el ámbito de nuestra misión, de aquello que somos en la vida, con nuestro propósito o manera personal de estar.'),
(33, 2, 'Iniciativa', 0, 1, 'Habilidad para originar ideas novedosas y desarrollar acciones para su implementación.'),
(34, 2, 'Pertinencia', 1, 1, 'Grado de compromiso hacia los Valores aceptados y reconocidos por el grupo de pertenencia.'),
(35, 2, 'Pertenencia', 2, 1, 'Nivel de experticia e idoneidad reconocida por los miembros del Equipo, en la disciplina de desempeño .'),
(36, 2, 'Trabajo en equipo', 3, 1, 'Disposición para integrarse a equipos de trabajo contribuyendo al cumplimiento de los objetivos, fortaleciendo las relaciones interpersonales de sus miembros.'),
(37, 2, 'Flexibilidad', 4, 1, 'Actitud para adherir positiva y flexiblemente a cambios en el contexto y en los procesos de trabajo.'),
(38, 2, 'Comunicación', 5, 1, 'Grado y modo de interacción con pares en el contexto de trabajo.'),
(39, 2, 'Liderazgo', 6, 1, 'Capacidad para guiar a sus colaboradores hacia el logro de los objetivos, generando un alto nivel de motivación.'),
(40, 2, 'Legitimación', 7, 1, 'Nivel de aceptación y reconocimiento de las decisiones y conductas, en su contexto de trabajo.'),
(41, 2, 'Creatividad', 0, 2, 'Habilidad para originar ideas novedosas y desarrollar acciones concretas para su implementación.'),
(42, 2, 'Orientación a los resultados', 1, 2, 'Capacidad para entender, anticiparse y satisfacer las necesidades del Cliente, tanto interno como externo.'),
(43, 2, 'Orientación al cliente', 2, 2, 'Actitud constante hacia el logro y la superación de los objetivos individuales y del grupo. Incluye la preocupación por el uso adecuado de los recursos y por el incremento sostenido de la productividad.'),
(44, 2, 'Orientación a la calidad', 3, 2, 'Actitud hacia la búsqueda permanente del mejoramiento de los productos/procesos y de la excelencia.'),
(45, 2, 'Resolución de conflictos', 4, 2, 'Resolver eficazmente diferencias en distintas posiciones, modificando posturas personales y/ o grupales en beneficio de todas las partes involucradas.'),
(46, 2, 'Gestión del cambio', 5, 2, 'Capacidad para promover e implementar los cambios requeridos a fin de mantener la ventaja competitiva de la empresa en el mercado.'),
(47, 2, 'Visión estratégica', 6, 2, 'Habilidad para imaginar escenarios futuros y formular estrategias orientadas a mejorar el posicionamiento de su equipo/área dentro de la Organización.'),
(48, 2, 'Identidad', 7, 2, 'Pleno compromiso e identificación con los Valores y la Cultura de la Organización.');

CREATE TABLE `transaction` (
  `id` int(11) NOT NULL,
  `uuid` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `payment_id` int(11) NOT NULL,
  `status` enum('init','pending','paid','rejected','error') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'init',
  `external_id` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `stamp` datetime NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `currency` varchar(3) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'USD',
  `rate` decimal(10,2) DEFAULT NULL,
  `commision` decimal(10,2) DEFAULT NULL,
  `commision_currency` varchar(3) COLLATE utf8_unicode_ci DEFAULT NULL,
  `creator_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `transaction` (`id`, `uuid`, `payment_id`, `status`, `external_id`, `stamp`, `amount`, `currency`, `rate`, `commision`, `commision_currency`, `creator_id`) VALUES
(1, '5f18be4b6e8575.54518703', 1, 'paid', NULL, '2020-07-22 22:31:39', '360.00', 'USD', NULL, NULL, NULL, 1);

CREATE TABLE `transaction_log` (
  `id` int(11) NOT NULL,
  `transaction_id` int(11) NOT NULL,
  `status` enum('pending','paid','rejected','error') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'pending',
  `external_id` text COLLATE utf8_unicode_ci,
  `external_data` text COLLATE utf8_unicode_ci,
  `stamp` datetime NOT NULL,
  `creator_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `transaction_log` (`id`, `transaction_id`, `status`, `external_id`, `external_data`, `stamp`, `creator_id`) VALUES
(1, 1, 'pending', NULL, NULL, '2020-07-22 22:31:39', 1),
(2, 1, 'paid', NULL, NULL, '2020-07-22 22:31:39', 1);

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `auth_key` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password_reset_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `surname` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` smallint(6) NOT NULL DEFAULT '10',
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  `is_administrator` tinyint(1) NOT NULL DEFAULT '0',
  `phone` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `notes` varchar(1000) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `user` (`id`, `username`, `auth_key`, `password_hash`, `password_reset_token`, `email`, `name`, `surname`, `status`, `created_at`, `updated_at`, `is_administrator`, `phone`, `notes`) VALUES
(1, 'admin', 'TKOsEC2v04JpORUhnbQEuuHS3PnaFGmf', '$2y$13$3FyxUh9XpoBYsn39Y7X1FO1Qa06SdFKpZohrbc3QCFd5I2vjhfbK2', NULL, 'admin@example.com', 'Administror', 'A', 10, 1429313351, 1492197214, 1, '(345)1234567', NULL),
(2, 'coach', 'bn7LboYGkEEvp2BIQtbhBF3qf8V4KL3-', '$2y$13$3FyxUh9XpoBYsn39Y7X1FO1Qa06SdFKpZohrbc3QCFd5I2vjhfbK2', NULL, 'coach@example.com', 'Coach', 'C', 10, 1430540056, 1492197337, 0, '(432)1098765', NULL),
(3, 'assisstant', 'Wb7v9hgzxjTrmiZ2NFxQhfoMN2oamovk', '$2y$13$3FyxUh9XpoBYsn39Y7X1FO1Qa06SdFKpZohrbc3QCFd5I2vjhfbK2', NULL, 'assisstant@example.com', 'Assisstant', 'A', 10, 0, 1492197406, 0, '(012)1234567', NULL);

CREATE TABLE `user_session` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `stamp` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `wheel` (
  `id` int(11) NOT NULL,
  `observer_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `observed_id` int(11) DEFAULT NULL,
  `sent_count` smallint(6) NOT NULL DEFAULT '0',
  `status` enum('created','sent','received','in_progress','done') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'created',
  `team_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `wheel` (`id`, `observer_id`, `date`, `created_at`, `updated_at`, `type`, `token`, `observed_id`, `sent_count`, `status`, `team_id`) VALUES
(11, 1, '2017-04-14', 1492197123, 1492197123, 0, '799-123-455', 1, 0, 'created', 1),
(12, 1, '2017-04-14', 1492197123, 1492197123, 1, '749-972-437', 1, 0, 'created', 1),
(13, 1, '2017-04-14', 1492197123, 1492197123, 1, '749-972-437', 2, 0, 'created', 1),
(14, 1, '2017-04-14', 1492197123, 1492197123, 1, '749-972-437', 3, 0, 'created', 1),
(15, 1, '2017-04-14', 1492197123, 1492197123, 2, '326-952-011', 1, 0, 'created', 1),
(16, 1, '2017-04-14', 1492197123, 1492197123, 2, '326-952-011', 2, 0, 'created', 1),
(17, 1, '2017-04-14', 1492197123, 1492197123, 2, '326-952-011', 3, 0, 'created', 1),
(18, 2, '2017-04-14', 1492197123, 1492197123, 0, '295-015-471', 2, 0, 'created', 1),
(19, 2, '2017-04-14', 1492197123, 1492197123, 1, '072-444-085', 1, 0, 'created', 1),
(20, 2, '2017-04-14', 1492197123, 1492197123, 1, '072-444-085', 2, 0, 'created', 1),
(21, 2, '2017-04-14', 1492197123, 1492197123, 1, '072-444-085', 3, 0, 'created', 1),
(22, 2, '2017-04-14', 1492197123, 1492197123, 2, '812-331-347', 1, 0, 'created', 1),
(23, 2, '2017-04-14', 1492197123, 1492197123, 2, '812-331-347', 2, 0, 'created', 1),
(24, 2, '2017-04-14', 1492197123, 1492197123, 2, '812-331-347', 3, 0, 'created', 1),
(25, 3, '2017-04-14', 1492197123, 1492197123, 0, '152-820-545', 3, 0, 'created', 1),
(26, 3, '2017-04-14', 1492197123, 1492197123, 1, '989-581-797', 1, 0, 'created', 1),
(27, 3, '2017-04-14', 1492197123, 1492197123, 1, '989-581-797', 2, 0, 'created', 1),
(28, 3, '2017-04-14', 1492197123, 1492197123, 1, '989-581-797', 3, 0, 'created', 1),
(29, 3, '2017-04-14', 1492197123, 1492197123, 2, '398-230-990', 1, 0, 'created', 1),
(30, 3, '2017-04-14', 1492197123, 1492197123, 2, '398-230-990', 2, 0, 'created', 1),
(31, 3, '2017-04-14', 1492197123, 1492197123, 2, '398-230-990', 3, 0, 'created', 1),
(32, 1, '2017-04-14', 1492197137, 1492197137, 0, '305-660-713', 1, 0, 'created', 2),
(33, 1, '2017-04-14', 1492197137, 1492197137, 1, '658-054-732', 1, 0, 'created', 2),
(34, 1, '2017-04-14', 1492197137, 1492197137, 1, '658-054-732', 2, 0, 'created', 2),
(35, 1, '2017-04-14', 1492197137, 1492197137, 1, '658-054-732', 3, 0, 'created', 2),
(36, 1, '2017-04-14', 1492197137, 1492197137, 2, '438-517-666', 1, 0, 'created', 2),
(37, 1, '2017-04-14', 1492197137, 1492197137, 2, '438-517-666', 2, 0, 'created', 2),
(38, 1, '2017-04-14', 1492197137, 1492197137, 2, '438-517-666', 3, 0, 'created', 2),
(39, 2, '2017-04-14', 1492197137, 1492197137, 0, '277-638-061', 2, 0, 'created', 2),
(40, 2, '2017-04-14', 1492197137, 1492197137, 1, '677-153-449', 1, 0, 'created', 2),
(41, 2, '2017-04-14', 1492197137, 1492197137, 1, '677-153-449', 2, 0, 'created', 2),
(42, 2, '2017-04-14', 1492197137, 1492197137, 1, '677-153-449', 3, 0, 'created', 2),
(43, 2, '2017-04-14', 1492197137, 1492197137, 2, '085-897-442', 1, 0, 'created', 2),
(44, 2, '2017-04-14', 1492197137, 1492197137, 2, '085-897-442', 2, 0, 'created', 2),
(45, 2, '2017-04-14', 1492197137, 1492197137, 2, '085-897-442', 3, 0, 'created', 2),
(46, 3, '2017-04-14', 1492197137, 1492197137, 0, '547-298-853', 3, 0, 'created', 2),
(47, 3, '2017-04-14', 1492197137, 1492197137, 1, '311-931-808', 1, 0, 'created', 2),
(48, 3, '2017-04-14', 1492197137, 1492197137, 1, '311-931-808', 2, 0, 'created', 2),
(49, 3, '2017-04-14', 1492197137, 1492197137, 1, '311-931-808', 3, 0, 'created', 2),
(50, 3, '2017-04-14', 1492197137, 1492197137, 2, '866-893-870', 1, 0, 'created', 2),
(51, 3, '2017-04-14', 1492197137, 1492197137, 2, '866-893-870', 2, 0, 'created', 2),
(52, 3, '2017-04-14', 1492197137, 1492197137, 2, '866-893-870', 3, 0, 'created', 2);

CREATE TABLE `wheel_answer` (
  `id` int(11) NOT NULL,
  `wheel_id` int(11) NOT NULL,
  `answer_order` smallint(6) NOT NULL,
  `answer_value` smallint(6) NOT NULL,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  `dimension` smallint(6) DEFAULT NULL,
  `question_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `wheel_question` (
  `id` int(11) NOT NULL,
  `dimension` int(11) NOT NULL,
  `order` int(11) NOT NULL,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  `type` int(11) NOT NULL DEFAULT '0',
  `question_id` int(11) NOT NULL,
  `team_type_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `wheel_question` (`id`, `dimension`, `order`, `created_at`, `updated_at`, `type`, `question_id`, `team_type_id`) VALUES
(1, 0, 0, 1492102211, 1492102211, 0, 1, 1),
(2, 0, 1, 1492102211, 1492102211, 0, 2, 1),
(3, 0, 2, 1492102211, 1492102211, 0, 3, 1),
(4, 0, 3, 1492102211, 1492102211, 0, 4, 1),
(5, 0, 4, 1492102211, 1492102211, 0, 5, 1),
(6, 0, 5, 1492102211, 1492102211, 0, 6, 1),
(7, 0, 6, 1492102211, 1492102211, 0, 7, 1),
(8, 0, 7, 1492102211, 1492102211, 0, 8, 1),
(9, 0, 8, 1492102211, 1492102211, 0, 9, 1),
(10, 0, 9, 1492102211, 1492102211, 0, 10, 1),
(11, 1, 10, 1492102212, 1492102212, 0, 11, 1),
(12, 1, 11, 1492102212, 1492102212, 0, 12, 1),
(13, 1, 12, 1492102212, 1492102212, 0, 13, 1),
(14, 1, 13, 1492102212, 1492102212, 0, 14, 1),
(15, 1, 14, 1492102212, 1492102212, 0, 15, 1),
(16, 1, 15, 1492102212, 1492102212, 0, 16, 1),
(17, 1, 16, 1492102212, 1492102212, 0, 17, 1),
(18, 1, 17, 1492102212, 1492102212, 0, 18, 1),
(19, 1, 18, 1492102212, 1492102212, 0, 19, 1),
(20, 1, 19, 1492102212, 1492102212, 0, 10, 1),
(21, 2, 20, 1492102212, 1492102212, 0, 20, 1),
(22, 2, 21, 1492102212, 1492102212, 0, 21, 1),
(23, 2, 22, 1492102212, 1492102212, 0, 22, 1),
(24, 2, 23, 1492102212, 1492102212, 0, 23, 1),
(25, 2, 24, 1492102212, 1492102212, 0, 24, 1),
(26, 2, 25, 1492102212, 1492102212, 0, 25, 1),
(27, 2, 26, 1492102212, 1492102212, 0, 26, 1),
(28, 2, 27, 1492102212, 1492102212, 0, 27, 1),
(29, 2, 28, 1492102212, 1492102212, 0, 28, 1),
(30, 2, 29, 1492102212, 1492102212, 0, 10, 1),
(31, 3, 30, 1492102212, 1492102212, 0, 29, 1),
(32, 3, 31, 1492102212, 1492102212, 0, 30, 1),
(33, 3, 32, 1492102212, 1492102212, 0, 31, 1),
(34, 3, 33, 1492102212, 1492102212, 0, 32, 1),
(35, 3, 34, 1492102213, 1492102213, 0, 33, 1),
(36, 3, 35, 1492102213, 1492102213, 0, 34, 1),
(37, 3, 36, 1492102213, 1492102213, 0, 35, 1),
(38, 3, 37, 1492102213, 1492102213, 0, 36, 1),
(39, 3, 38, 1492102213, 1492102213, 0, 37, 1),
(40, 3, 39, 1492102213, 1492102213, 0, 10, 1),
(41, 4, 40, 1492102213, 1492102213, 0, 38, 1),
(42, 4, 41, 1492102213, 1492102213, 0, 39, 1),
(43, 4, 42, 1492102213, 1492102213, 0, 40, 1),
(44, 4, 43, 1492102213, 1492102213, 0, 41, 1),
(45, 4, 44, 1492102213, 1492102213, 0, 42, 1),
(46, 4, 45, 1492102213, 1492102213, 0, 43, 1),
(47, 4, 46, 1492102213, 1492102213, 0, 44, 1),
(48, 4, 47, 1492102213, 1492102213, 0, 45, 1),
(49, 4, 48, 1492102213, 1492102213, 0, 46, 1),
(50, 4, 49, 1492102213, 1492102213, 0, 10, 1),
(51, 5, 50, 1492102213, 1492102213, 0, 47, 1),
(52, 5, 51, 1492102213, 1492102213, 0, 48, 1),
(53, 5, 52, 1492102213, 1492102213, 0, 49, 1),
(54, 5, 53, 1492102213, 1492102213, 0, 50, 1),
(55, 5, 54, 1492102213, 1492102213, 0, 51, 1),
(56, 5, 55, 1492102213, 1492102213, 0, 52, 1),
(57, 5, 56, 1492102214, 1492102214, 0, 53, 1),
(58, 5, 57, 1492102214, 1492102214, 0, 54, 1),
(59, 5, 58, 1492102214, 1492102214, 0, 55, 1),
(60, 5, 59, 1492102214, 1492102214, 0, 10, 1),
(61, 6, 60, 1492102214, 1492102214, 0, 56, 1),
(62, 6, 61, 1492102214, 1492102214, 0, 57, 1),
(63, 6, 62, 1492102214, 1492102214, 0, 58, 1),
(64, 6, 63, 1492102214, 1492102214, 0, 59, 1),
(65, 6, 64, 1492102214, 1492102214, 0, 60, 1),
(66, 6, 65, 1492102214, 1492102214, 0, 61, 1),
(67, 6, 66, 1492102214, 1492102214, 0, 62, 1),
(68, 6, 67, 1492102214, 1492102214, 0, 63, 1),
(69, 6, 68, 1492102214, 1492102214, 0, 64, 1),
(70, 6, 69, 1492102214, 1492102214, 0, 10, 1),
(71, 7, 70, 1492102214, 1492102214, 0, 65, 1),
(72, 7, 71, 1492102214, 1492102214, 0, 66, 1),
(73, 7, 72, 1492102214, 1492102214, 0, 67, 1),
(74, 7, 73, 1492102214, 1492102214, 0, 68, 1),
(75, 7, 74, 1492102214, 1492102214, 0, 69, 1),
(76, 7, 75, 1492102214, 1492102214, 0, 70, 1),
(77, 7, 76, 1492102215, 1492102215, 0, 71, 1),
(78, 7, 77, 1492102215, 1492102215, 0, 72, 1),
(79, 7, 78, 1492102215, 1492102215, 0, 73, 1),
(80, 7, 79, 1492102215, 1492102215, 0, 10, 1),
(81, 0, 0, 1492102376, 1492102376, 1, 74, 1),
(82, 0, 1, 1492102376, 1492102376, 1, 75, 1),
(83, 0, 2, 1492102376, 1492102376, 1, 76, 1),
(84, 0, 3, 1492102376, 1492102376, 1, 77, 1),
(85, 0, 4, 1492102376, 1492102376, 1, 78, 1),
(86, 0, 5, 1492102376, 1492102376, 1, 79, 1),
(87, 0, 6, 1492102377, 1492102377, 1, 80, 1),
(88, 0, 7, 1492102377, 1492102377, 1, 81, 1),
(89, 1, 8, 1492102377, 1492102377, 1, 82, 1),
(90, 1, 9, 1492102377, 1492102377, 1, 83, 1),
(91, 1, 10, 1492102377, 1492102377, 1, 84, 1),
(92, 1, 11, 1492102377, 1492102377, 1, 85, 1),
(93, 1, 12, 1492102377, 1492102377, 1, 86, 1),
(94, 1, 13, 1492102377, 1492102377, 1, 87, 1),
(95, 1, 14, 1492102377, 1492102377, 1, 88, 1),
(96, 1, 15, 1492102377, 1492102377, 1, 89, 1),
(97, 2, 16, 1492102377, 1492102377, 1, 90, 1),
(98, 2, 17, 1492102377, 1492102377, 1, 91, 1),
(99, 2, 18, 1492102377, 1492102377, 1, 92, 1),
(100, 2, 19, 1492102377, 1492102377, 1, 93, 1),
(101, 2, 20, 1492102377, 1492102377, 1, 94, 1),
(102, 2, 21, 1492102377, 1492102377, 1, 95, 1),
(103, 2, 22, 1492102377, 1492102377, 1, 96, 1),
(104, 2, 23, 1492102377, 1492102377, 1, 97, 1),
(105, 3, 24, 1492102378, 1492102378, 1, 98, 1),
(106, 3, 25, 1492102378, 1492102378, 1, 99, 1),
(107, 3, 26, 1492102378, 1492102378, 1, 100, 1),
(108, 3, 27, 1492102378, 1492102378, 1, 101, 1),
(109, 3, 28, 1492102378, 1492102378, 1, 102, 1),
(110, 3, 29, 1492102378, 1492102378, 1, 103, 1),
(111, 3, 30, 1492102378, 1492102378, 1, 104, 1),
(112, 3, 31, 1492102378, 1492102378, 1, 105, 1),
(113, 4, 32, 1492102378, 1492102378, 1, 106, 1),
(114, 4, 33, 1492102378, 1492102378, 1, 107, 1),
(115, 4, 34, 1492102378, 1492102378, 1, 108, 1),
(116, 4, 35, 1492102378, 1492102378, 1, 109, 1),
(117, 4, 36, 1492102378, 1492102378, 1, 110, 1),
(118, 4, 37, 1492102378, 1492102378, 1, 111, 1),
(119, 4, 38, 1492102378, 1492102378, 1, 112, 1),
(120, 4, 39, 1492102378, 1492102378, 1, 113, 1),
(121, 5, 40, 1492102378, 1492102378, 1, 114, 1),
(122, 5, 41, 1492102378, 1492102378, 1, 115, 1),
(123, 5, 42, 1492102378, 1492102378, 1, 116, 1),
(124, 5, 43, 1492102378, 1492102378, 1, 117, 1),
(125, 5, 44, 1492102378, 1492102378, 1, 118, 1),
(126, 5, 45, 1492102379, 1492102379, 1, 119, 1),
(127, 5, 46, 1492102379, 1492102379, 1, 120, 1),
(128, 5, 47, 1492102379, 1492102379, 1, 121, 1),
(129, 6, 48, 1492102379, 1492102379, 1, 122, 1),
(130, 6, 49, 1492102379, 1492102379, 1, 123, 1),
(131, 6, 50, 1492102379, 1492102379, 1, 124, 1),
(132, 6, 51, 1492102379, 1492102379, 1, 125, 1),
(133, 6, 52, 1492102379, 1492102379, 1, 126, 1),
(134, 6, 53, 1492102379, 1492102379, 1, 127, 1),
(135, 6, 54, 1492102379, 1492102379, 1, 128, 1),
(136, 6, 55, 1492102379, 1492102379, 1, 129, 1),
(137, 7, 56, 1492102379, 1492102379, 1, 130, 1),
(138, 7, 57, 1492102379, 1492102379, 1, 131, 1),
(139, 7, 58, 1492102379, 1492102379, 1, 132, 1),
(140, 7, 59, 1492102379, 1492102379, 1, 133, 1),
(141, 7, 60, 1492102379, 1492102379, 1, 134, 1),
(142, 7, 61, 1492102379, 1492102379, 1, 135, 1),
(143, 7, 62, 1492102379, 1492102379, 1, 136, 1),
(144, 7, 63, 1492102379, 1492102379, 1, 137, 1),
(145, 0, 0, 1492102379, 1492102379, 2, 138, 1),
(146, 0, 1, 1492102379, 1492102379, 2, 139, 1),
(147, 0, 2, 1492102380, 1492102380, 2, 140, 1),
(148, 0, 3, 1492102380, 1492102380, 2, 141, 1),
(149, 0, 4, 1492102380, 1492102380, 2, 142, 1),
(150, 0, 5, 1492102380, 1492102380, 2, 143, 1),
(151, 0, 6, 1492102380, 1492102380, 2, 144, 1),
(152, 0, 7, 1492102380, 1492102380, 2, 145, 1),
(153, 1, 8, 1492102380, 1492102380, 2, 146, 1),
(154, 1, 9, 1492102380, 1492102380, 2, 147, 1),
(155, 1, 10, 1492102380, 1492102380, 2, 148, 1),
(156, 1, 11, 1492102380, 1492102380, 2, 149, 1),
(157, 1, 12, 1492102380, 1492102380, 2, 150, 1),
(158, 1, 13, 1492102380, 1492102380, 2, 151, 1),
(159, 1, 14, 1492102380, 1492102380, 2, 152, 1),
(160, 1, 15, 1492102380, 1492102380, 2, 153, 1),
(161, 2, 16, 1492102380, 1492102380, 2, 154, 1),
(162, 2, 17, 1492102380, 1492102380, 2, 155, 1),
(163, 2, 18, 1492102380, 1492102380, 2, 156, 1),
(164, 2, 19, 1492102380, 1492102380, 2, 157, 1),
(165, 2, 20, 1492102380, 1492102380, 2, 158, 1),
(166, 2, 21, 1492102380, 1492102380, 2, 159, 1),
(167, 2, 22, 1492102380, 1492102380, 2, 160, 1),
(168, 2, 23, 1492102380, 1492102380, 2, 161, 1),
(169, 3, 24, 1492102380, 1492102380, 2, 162, 1),
(170, 3, 25, 1492102381, 1492102381, 2, 163, 1),
(171, 3, 26, 1492102381, 1492102381, 2, 164, 1),
(172, 3, 27, 1492102381, 1492102381, 2, 165, 1),
(173, 3, 28, 1492102381, 1492102381, 2, 166, 1),
(174, 3, 29, 1492102381, 1492102381, 2, 167, 1),
(175, 3, 30, 1492102381, 1492102381, 2, 168, 1),
(176, 3, 31, 1492102381, 1492102381, 2, 169, 1),
(177, 5, 40, 1492102381, 1492102381, 2, 170, 1),
(178, 5, 41, 1492102381, 1492102381, 2, 171, 1),
(179, 5, 42, 1492102381, 1492102381, 2, 172, 1),
(180, 5, 43, 1492102381, 1492102381, 2, 173, 1),
(181, 5, 44, 1492102381, 1492102381, 2, 174, 1),
(182, 5, 45, 1492102381, 1492102381, 2, 175, 1),
(183, 5, 46, 1492102381, 1492102381, 2, 176, 1),
(184, 5, 47, 1492102381, 1492102381, 2, 177, 1),
(185, 4, 32, 1492102381, 1492102381, 2, 178, 1),
(186, 4, 33, 1492102381, 1492102381, 2, 179, 1),
(187, 4, 34, 1492102382, 1492102382, 2, 180, 1),
(188, 4, 35, 1492102382, 1492102382, 2, 181, 1),
(189, 4, 36, 1492102382, 1492102382, 2, 182, 1),
(190, 4, 37, 1492102382, 1492102382, 2, 183, 1),
(191, 4, 38, 1492102382, 1492102382, 2, 184, 1),
(192, 4, 39, 1492102382, 1492102382, 2, 185, 1),
(193, 6, 48, 1492102382, 1492102382, 2, 186, 1),
(194, 6, 49, 1492102382, 1492102382, 2, 139, 1),
(195, 6, 50, 1492102382, 1492102382, 2, 187, 1),
(196, 6, 51, 1492102382, 1492102382, 2, 188, 1),
(197, 6, 52, 1492102382, 1492102382, 2, 189, 1),
(198, 6, 53, 1492102382, 1492102382, 2, 190, 1),
(199, 6, 54, 1492102382, 1492102382, 2, 191, 1),
(200, 6, 55, 1492102382, 1492102382, 2, 192, 1),
(201, 7, 56, 1492102382, 1492102382, 2, 193, 1),
(202, 7, 57, 1492102382, 1492102382, 2, 194, 1),
(203, 7, 58, 1492102382, 1492102382, 2, 195, 1),
(204, 7, 59, 1492102382, 1492102382, 2, 196, 1),
(205, 7, 60, 1492102383, 1492102383, 2, 197, 1),
(206, 7, 61, 1492102383, 1492102383, 2, 198, 1),
(207, 7, 62, 1492102383, 1492102383, 2, 199, 1),
(208, 7, 63, 1492102383, 1492102383, 2, 200, 1),
(209, 0, 0, 1543897988, 1543897988, 0, 1, 2),
(210, 0, 1, 1543897988, 1543897988, 0, 2, 2),
(211, 0, 2, 1543897988, 1543897988, 0, 3, 2),
(212, 0, 3, 1543897988, 1543897988, 0, 4, 2),
(213, 0, 4, 1543897988, 1543897988, 0, 5, 2),
(214, 0, 5, 1543897988, 1543897988, 0, 6, 2),
(215, 0, 6, 1543897988, 1543897988, 0, 7, 2),
(216, 0, 7, 1543897988, 1543897988, 0, 8, 2),
(217, 0, 8, 1543897988, 1543897988, 0, 9, 2),
(218, 0, 9, 1543897988, 1543897988, 0, 10, 2),
(219, 1, 10, 1543897988, 1543897988, 0, 11, 2),
(220, 1, 11, 1543897988, 1543897988, 0, 12, 2),
(221, 1, 12, 1543897988, 1543897988, 0, 13, 2),
(222, 1, 13, 1543897988, 1543897988, 0, 14, 2),
(223, 1, 14, 1543897988, 1543897988, 0, 15, 2),
(224, 1, 15, 1543897988, 1543897988, 0, 16, 2),
(225, 1, 16, 1543897988, 1543897988, 0, 17, 2),
(226, 1, 17, 1543897988, 1543897988, 0, 18, 2),
(227, 1, 18, 1543897988, 1543897988, 0, 19, 2),
(228, 1, 19, 1543897988, 1543897988, 0, 10, 2),
(229, 2, 20, 1543897988, 1543897988, 0, 20, 2),
(230, 2, 21, 1543897988, 1543897988, 0, 21, 2),
(231, 2, 22, 1543897988, 1543897988, 0, 22, 2),
(232, 2, 23, 1543897988, 1543897988, 0, 23, 2),
(233, 2, 24, 1543897988, 1543897988, 0, 24, 2),
(234, 2, 25, 1543897988, 1543897988, 0, 25, 2),
(235, 2, 26, 1543897988, 1543897988, 0, 26, 2),
(236, 2, 27, 1543897988, 1543897988, 0, 27, 2),
(237, 2, 28, 1543897988, 1543897988, 0, 28, 2),
(238, 2, 29, 1543897988, 1543897988, 0, 10, 2),
(239, 3, 30, 1543897988, 1543897988, 0, 29, 2),
(240, 3, 31, 1543897988, 1543897988, 0, 30, 2),
(241, 3, 32, 1543897988, 1543897988, 0, 31, 2),
(242, 3, 33, 1543897988, 1543897988, 0, 32, 2),
(243, 3, 34, 1543897988, 1543897988, 0, 33, 2),
(244, 3, 35, 1543897988, 1543897988, 0, 34, 2),
(245, 3, 36, 1543897988, 1543897988, 0, 35, 2),
(246, 3, 37, 1543897988, 1543897988, 0, 36, 2),
(247, 3, 38, 1543897988, 1543897988, 0, 37, 2),
(248, 3, 39, 1543897988, 1543897988, 0, 10, 2),
(249, 4, 40, 1543897988, 1543897988, 0, 38, 2),
(250, 4, 41, 1543897988, 1543897988, 0, 39, 2),
(251, 4, 42, 1543897988, 1543897988, 0, 40, 2),
(252, 4, 43, 1543897988, 1543897988, 0, 41, 2),
(253, 4, 44, 1543897988, 1543897988, 0, 42, 2),
(254, 4, 45, 1543897988, 1543897988, 0, 43, 2),
(255, 4, 46, 1543897988, 1543897988, 0, 44, 2),
(256, 4, 47, 1543897988, 1543897988, 0, 45, 2),
(257, 4, 48, 1543897988, 1543897988, 0, 46, 2),
(258, 4, 49, 1543897988, 1543897988, 0, 10, 2),
(259, 5, 50, 1543897988, 1543897988, 0, 47, 2),
(260, 5, 51, 1543897988, 1543897988, 0, 48, 2),
(261, 5, 52, 1543897988, 1543897988, 0, 49, 2),
(262, 5, 53, 1543897988, 1543897988, 0, 50, 2),
(263, 5, 54, 1543897988, 1543897988, 0, 51, 2),
(264, 5, 55, 1543897988, 1543897988, 0, 52, 2),
(265, 5, 56, 1543897988, 1543897988, 0, 53, 2),
(266, 5, 57, 1543897988, 1543897988, 0, 54, 2),
(267, 5, 58, 1543897988, 1543897988, 0, 55, 2),
(268, 5, 59, 1543897988, 1543897988, 0, 10, 2),
(269, 6, 60, 1543897988, 1543897988, 0, 56, 2),
(270, 6, 61, 1543897988, 1543897988, 0, 57, 2),
(271, 6, 62, 1543897988, 1543897988, 0, 58, 2),
(272, 6, 63, 1543897988, 1543897988, 0, 59, 2),
(273, 6, 64, 1543897988, 1543897988, 0, 60, 2),
(274, 6, 65, 1543897988, 1543897988, 0, 61, 2),
(275, 6, 66, 1543897988, 1543897988, 0, 62, 2),
(276, 6, 67, 1543897988, 1543897988, 0, 63, 2),
(277, 6, 68, 1543897988, 1543897988, 0, 64, 2),
(278, 6, 69, 1543897988, 1543897988, 0, 10, 2),
(279, 7, 70, 1543897988, 1543897988, 0, 65, 2),
(280, 7, 71, 1543897988, 1543897988, 0, 66, 2),
(281, 7, 72, 1543897988, 1543897988, 0, 67, 2),
(282, 7, 73, 1543897988, 1543897988, 0, 68, 2),
(283, 7, 74, 1543897988, 1543897988, 0, 69, 2),
(284, 7, 75, 1543897988, 1543897988, 0, 70, 2),
(285, 7, 76, 1543897988, 1543897988, 0, 71, 2),
(286, 7, 77, 1543897988, 1543897988, 0, 72, 2),
(287, 7, 78, 1543897988, 1543897988, 0, 73, 2),
(288, 7, 79, 1543897988, 1543897988, 0, 10, 2),
(289, 0, 0, 1543897988, 1543897988, 1, 74, 2),
(290, 0, 1, 1543897988, 1543897988, 1, 75, 2),
(291, 0, 2, 1543897988, 1543897988, 1, 76, 2),
(292, 0, 3, 1543897988, 1543897988, 1, 77, 2),
(293, 0, 4, 1543897988, 1543897988, 1, 78, 2),
(294, 0, 5, 1543897988, 1543897988, 1, 79, 2),
(295, 0, 6, 1543897988, 1543897988, 1, 80, 2),
(296, 0, 7, 1543897988, 1543897988, 1, 81, 2),
(297, 1, 8, 1543897988, 1543897988, 1, 82, 2),
(298, 1, 9, 1543897988, 1543897988, 1, 83, 2),
(299, 1, 10, 1543897988, 1543897988, 1, 84, 2),
(300, 1, 11, 1543897988, 1543897988, 1, 85, 2),
(301, 1, 12, 1543897988, 1543897988, 1, 86, 2),
(302, 1, 13, 1543897988, 1543897988, 1, 87, 2),
(303, 1, 14, 1543897988, 1543897988, 1, 88, 2),
(304, 1, 15, 1543897988, 1543897988, 1, 89, 2),
(305, 2, 16, 1543897988, 1543897988, 1, 90, 2),
(306, 2, 17, 1543897988, 1543897988, 1, 91, 2),
(307, 2, 18, 1543897988, 1543897988, 1, 92, 2),
(308, 2, 19, 1543897988, 1543897988, 1, 93, 2),
(309, 2, 20, 1543897988, 1543897988, 1, 94, 2),
(310, 2, 21, 1543897988, 1543897988, 1, 95, 2),
(311, 2, 22, 1543897988, 1543897988, 1, 96, 2),
(312, 2, 23, 1543897988, 1543897988, 1, 97, 2),
(313, 3, 24, 1543897988, 1543897988, 1, 98, 2),
(314, 3, 25, 1543897988, 1543897988, 1, 99, 2),
(315, 3, 26, 1543897988, 1543897988, 1, 100, 2),
(316, 3, 27, 1543897988, 1543897988, 1, 101, 2),
(317, 3, 28, 1543897988, 1543897988, 1, 102, 2),
(318, 3, 29, 1543897988, 1543897988, 1, 103, 2),
(319, 3, 30, 1543897988, 1543897988, 1, 104, 2),
(320, 3, 31, 1543897988, 1543897988, 1, 105, 2),
(321, 4, 32, 1543897988, 1543897988, 1, 106, 2),
(322, 4, 33, 1543897988, 1543897988, 1, 107, 2),
(323, 4, 34, 1543897988, 1543897988, 1, 108, 2),
(324, 4, 35, 1543897988, 1543897988, 1, 109, 2),
(325, 4, 36, 1543897988, 1543897988, 1, 110, 2),
(326, 4, 37, 1543897988, 1543897988, 1, 111, 2),
(327, 4, 38, 1543897988, 1543897988, 1, 112, 2),
(328, 4, 39, 1543897988, 1543897988, 1, 113, 2),
(329, 5, 40, 1543897988, 1543897988, 1, 114, 2),
(330, 5, 41, 1543897988, 1543897988, 1, 115, 2),
(331, 5, 42, 1543897988, 1543897988, 1, 116, 2),
(332, 5, 43, 1543897988, 1543897988, 1, 117, 2),
(333, 5, 44, 1543897988, 1543897988, 1, 118, 2),
(334, 5, 45, 1543897988, 1543897988, 1, 119, 2),
(335, 5, 46, 1543897988, 1543897988, 1, 120, 2),
(336, 5, 47, 1543897988, 1543897988, 1, 121, 2),
(337, 6, 48, 1543897988, 1543897988, 1, 122, 2),
(338, 6, 49, 1543897988, 1543897988, 1, 123, 2),
(339, 6, 50, 1543897988, 1543897988, 1, 124, 2),
(340, 6, 51, 1543897988, 1543897988, 1, 125, 2),
(341, 6, 52, 1543897988, 1543897988, 1, 126, 2),
(342, 6, 53, 1543897988, 1543897988, 1, 127, 2),
(343, 6, 54, 1543897988, 1543897988, 1, 128, 2),
(344, 6, 55, 1543897988, 1543897988, 1, 129, 2),
(345, 7, 56, 1543897988, 1543897988, 1, 130, 2),
(346, 7, 57, 1543897988, 1543897988, 1, 131, 2),
(347, 7, 58, 1543897988, 1543897988, 1, 132, 2),
(348, 7, 59, 1543897988, 1543897988, 1, 133, 2),
(349, 7, 60, 1543897988, 1543897988, 1, 134, 2),
(350, 7, 61, 1543897988, 1543897988, 1, 135, 2),
(351, 7, 62, 1543897988, 1543897988, 1, 136, 2),
(352, 7, 63, 1543897988, 1543897988, 1, 137, 2),
(353, 0, 0, 1543897988, 1543897988, 2, 138, 2),
(354, 0, 1, 1543897988, 1543897988, 2, 139, 2),
(355, 0, 2, 1543897988, 1543897988, 2, 140, 2),
(356, 0, 3, 1543897988, 1543897988, 2, 141, 2),
(357, 0, 4, 1543897988, 1543897988, 2, 142, 2),
(358, 0, 5, 1543897988, 1543897988, 2, 143, 2),
(359, 0, 6, 1543897988, 1543897988, 2, 144, 2),
(360, 0, 7, 1543897988, 1543897988, 2, 145, 2),
(361, 1, 8, 1543897988, 1543897988, 2, 146, 2),
(362, 1, 9, 1543897988, 1543897988, 2, 147, 2),
(363, 1, 10, 1543897988, 1543897988, 2, 148, 2),
(364, 1, 11, 1543897988, 1543897988, 2, 149, 2),
(365, 1, 12, 1543897988, 1543897988, 2, 150, 2),
(366, 1, 13, 1543897988, 1543897988, 2, 151, 2),
(367, 1, 14, 1543897988, 1543897988, 2, 152, 2),
(368, 1, 15, 1543897988, 1543897988, 2, 153, 2),
(369, 2, 16, 1543897988, 1543897988, 2, 154, 2),
(370, 2, 17, 1543897988, 1543897988, 2, 155, 2),
(371, 2, 18, 1543897988, 1543897988, 2, 156, 2),
(372, 2, 19, 1543897988, 1543897988, 2, 157, 2),
(373, 2, 20, 1543897988, 1543897988, 2, 158, 2),
(374, 2, 21, 1543897988, 1543897988, 2, 159, 2),
(375, 2, 22, 1543897988, 1543897988, 2, 160, 2),
(376, 2, 23, 1543897988, 1543897988, 2, 161, 2),
(377, 3, 24, 1543897988, 1543897988, 2, 162, 2),
(378, 3, 25, 1543897988, 1543897988, 2, 163, 2),
(379, 3, 26, 1543897988, 1543897988, 2, 164, 2),
(380, 3, 27, 1543897988, 1543897988, 2, 165, 2),
(381, 3, 28, 1543897988, 1543897988, 2, 166, 2),
(382, 3, 29, 1543897988, 1543897988, 2, 167, 2),
(383, 3, 30, 1543897988, 1543897988, 2, 168, 2),
(384, 3, 31, 1543897988, 1543897988, 2, 169, 2),
(385, 4, 32, 1543897988, 1543897988, 2, 178, 2),
(386, 4, 33, 1543897988, 1543897988, 2, 179, 2),
(387, 4, 34, 1543897988, 1543897988, 2, 180, 2),
(388, 4, 35, 1543897988, 1543897988, 2, 181, 2),
(389, 4, 36, 1543897988, 1543897988, 2, 182, 2),
(390, 4, 37, 1543897988, 1543897988, 2, 183, 2),
(391, 4, 38, 1543897988, 1543897988, 2, 184, 2),
(392, 4, 39, 1543897988, 1543897988, 2, 185, 2),
(393, 5, 40, 1543897988, 1543897988, 2, 170, 2),
(394, 5, 41, 1543897988, 1543897988, 2, 171, 2),
(395, 5, 42, 1543897988, 1543897988, 2, 172, 2),
(396, 5, 43, 1543897988, 1543897988, 2, 173, 2),
(397, 5, 44, 1543897988, 1543897988, 2, 174, 2),
(398, 5, 45, 1543897988, 1543897988, 2, 175, 2),
(399, 5, 46, 1543897988, 1543897988, 2, 176, 2),
(400, 5, 47, 1543897988, 1543897988, 2, 177, 2),
(401, 6, 48, 1543897988, 1543897988, 2, 186, 2),
(402, 6, 49, 1543897988, 1543897988, 2, 139, 2),
(403, 6, 50, 1543897988, 1543897988, 2, 187, 2),
(404, 6, 51, 1543897988, 1543897988, 2, 188, 2),
(405, 6, 52, 1543897988, 1543897988, 2, 189, 2),
(406, 6, 53, 1543897988, 1543897988, 2, 190, 2),
(407, 6, 54, 1543897988, 1543897988, 2, 191, 2),
(408, 6, 55, 1543897988, 1543897988, 2, 192, 2),
(409, 7, 56, 1543897988, 1543897988, 2, 193, 2),
(410, 7, 57, 1543897988, 1543897988, 2, 194, 2),
(411, 7, 58, 1543897988, 1543897988, 2, 195, 2),
(412, 7, 59, 1543897988, 1543897988, 2, 196, 2),
(413, 7, 60, 1543897988, 1543897988, 2, 197, 2),
(414, 7, 61, 1543897988, 1543897988, 2, 198, 2),
(415, 7, 62, 1543897988, 1543897988, 2, 199, 2),
(416, 7, 63, 1543897988, 1543897988, 2, 200, 2);


ALTER TABLE `company`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_company_coach` (`coach_id`);

ALTER TABLE `currency`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `feedback`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_feedback_user` (`user_id`);

ALTER TABLE `individual_report`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_individual_report_report` (`report_id`),
  ADD KEY `fk_individual_report_user` (`person_id`),
  ADD KEY `fk_individual_report_perception` (`perception_id`),
  ADD KEY `fk_individual_report_relations` (`relations_id`),
  ADD KEY `fk_individual_report_competences` (`competences_id`),
  ADD KEY `fk_individual_report_emergents` (`emergents_id`),
  ADD KEY `fk_individual_report_performance` (`performance_id`),
  ADD KEY `fk_individual_report_perception_keywords` (`perception_keywords_id`),
  ADD KEY `fk_individual_report_relations_keywords` (`relations_keywords_id`),
  ADD KEY `fk_individual_report_competences_keywords` (`competences_keywords_id`),
  ADD KEY `fk_individual_report_emergents_keywords` (`emergents_keywords_id`),
  ADD KEY `fk_individual_report_performance_keywords` (`performance_keywords_id`);

ALTER TABLE `liquidation`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_log_user` (`coach_id`);

ALTER TABLE `migration`
  ADD PRIMARY KEY (`version`);

ALTER TABLE `payment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_payment_coach` (`coach_id`),
  ADD KEY `fk_payment_liquidation` (`liquidation_id`),
  ADD KEY `fk_payment_creator` (`creator_id`);

ALTER TABLE `payment_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_payment_log_payment` (`payment_id`),
  ADD KEY `fk_payment_log_creator` (`creator_id`);

ALTER TABLE `person`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_person_coach` (`coach_id`);

ALTER TABLE `product`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `question`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `report`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_report_team` (`team_id`),
  ADD KEY `fk_report_introduction` (`introduction_id`),
  ADD KEY `fk_report_effectiveness` (`effectiveness_id`),
  ADD KEY `fk_report_performance` (`performance_id`),
  ADD KEY `fk_report_competences` (`competences_id`),
  ADD KEY `fk_report_emergents` (`emergents_id`),
  ADD KEY `fk_report_relations` (`relations_id`),
  ADD KEY `fk_report_introduction_keywords` (`introduction_keywords_id`),
  ADD KEY `fk_report_effectiveness_keywords` (`effectiveness_keywords_id`),
  ADD KEY `fk_report_performance_keywords` (`performance_keywords_id`),
  ADD KEY `fk_report_competences_keywords` (`competences_keywords_id`),
  ADD KEY `fk_report_emergents_keywords` (`emergents_keywords_id`),
  ADD KEY `fk_report_relations_keywords` (`relations_keywords_id`),
  ADD KEY `fk_report_action_plan` (`action_plan_id`);

ALTER TABLE `report_field`
  ADD PRIMARY KEY (`report_field_id`);

ALTER TABLE `stock`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_stock_coach` (`coach_id`),
  ADD KEY `fk_stock_creator` (`creator_id`),
  ADD KEY `fk_stock_consumer` (`consumer_id`),
  ADD KEY `fk_stock_team` (`team_id`),
  ADD KEY `fk_stock_product` (`product_id`),
  ADD KEY `fk_stock_payment` (`payment_id`);

ALTER TABLE `team`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_team_team_type` (`team_type_id`);

ALTER TABLE `team_coach`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `team_member`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `team_type`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_team_type_product` (`product_id`);

ALTER TABLE `team_type_dimension`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_team_type_dimension_team_type` (`team_type_id`);

ALTER TABLE `transaction`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_transaction_payment` (`payment_id`),
  ADD KEY `fk_transaction_creator` (`creator_id`);

ALTER TABLE `transaction_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_transaction_log_transaction` (`transaction_id`),
  ADD KEY `fk_transaction_log_creator` (`creator_id`);

ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_user_username` (`username`);

ALTER TABLE `user_session`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_user_session_user` (`user_id`);

ALTER TABLE `wheel`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_wheel_observed` (`observed_id`),
  ADD KEY `fk_wheel_observer` (`observer_id`),
  ADD KEY `fk_wheel_team` (`team_id`);

ALTER TABLE `wheel_answer`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_wheel_answer_wheel` (`wheel_id`),
  ADD KEY `fk_wheel_answer_question` (`question_id`);

ALTER TABLE `wheel_question`
  ADD PRIMARY KEY (`id`),
  ADD KEY `index_order` (`order`,`type`),
  ADD KEY `fk_wheel_question_question` (`question_id`),
  ADD KEY `fk_wheel_question_team_type` (`team_type_id`);


ALTER TABLE `company`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `currency`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `feedback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `individual_report`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `liquidation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `payment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `payment_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `person`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `question`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `report`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `report_field`
  MODIFY `report_field_id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `stock`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `team`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `team_coach`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `team_member`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `team_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `team_type_dimension`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `transaction`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `transaction_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `user_session`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `wheel`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `wheel_answer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `wheel_question`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;


ALTER TABLE `company`
  ADD CONSTRAINT `fk_company_coach` FOREIGN KEY (`coach_id`) REFERENCES `user` (`id`);

ALTER TABLE `feedback`
  ADD CONSTRAINT `fk_feedback_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

ALTER TABLE `individual_report`
  ADD CONSTRAINT `fk_individual_report_competences` FOREIGN KEY (`competences_id`) REFERENCES `report_field` (`report_field_id`),
  ADD CONSTRAINT `fk_individual_report_competences_keywords` FOREIGN KEY (`competences_keywords_id`) REFERENCES `report_field` (`report_field_id`),
  ADD CONSTRAINT `fk_individual_report_emergents` FOREIGN KEY (`emergents_id`) REFERENCES `report_field` (`report_field_id`),
  ADD CONSTRAINT `fk_individual_report_emergents_keywords` FOREIGN KEY (`emergents_keywords_id`) REFERENCES `report_field` (`report_field_id`),
  ADD CONSTRAINT `fk_individual_report_perception` FOREIGN KEY (`perception_id`) REFERENCES `report_field` (`report_field_id`),
  ADD CONSTRAINT `fk_individual_report_perception_keywords` FOREIGN KEY (`perception_keywords_id`) REFERENCES `report_field` (`report_field_id`),
  ADD CONSTRAINT `fk_individual_report_performance` FOREIGN KEY (`performance_id`) REFERENCES `report_field` (`report_field_id`),
  ADD CONSTRAINT `fk_individual_report_performance_keywords` FOREIGN KEY (`performance_keywords_id`) REFERENCES `report_field` (`report_field_id`),
  ADD CONSTRAINT `fk_individual_report_relations` FOREIGN KEY (`relations_id`) REFERENCES `report_field` (`report_field_id`),
  ADD CONSTRAINT `fk_individual_report_relations_keywords` FOREIGN KEY (`relations_keywords_id`) REFERENCES `report_field` (`report_field_id`),
  ADD CONSTRAINT `fk_individual_report_report` FOREIGN KEY (`report_id`) REFERENCES `report` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_individual_report_user` FOREIGN KEY (`person_id`) REFERENCES `person` (`id`) ON DELETE CASCADE;

ALTER TABLE `log`
  ADD CONSTRAINT `fk_log_user` FOREIGN KEY (`coach_id`) REFERENCES `user` (`id`);

ALTER TABLE `payment`
  ADD CONSTRAINT `fk_payment_coach` FOREIGN KEY (`coach_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `fk_payment_creator` FOREIGN KEY (`creator_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `fk_payment_liquidation` FOREIGN KEY (`liquidation_id`) REFERENCES `liquidation` (`id`);

ALTER TABLE `payment_log`
  ADD CONSTRAINT `fk_payment_log_creator` FOREIGN KEY (`creator_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `fk_payment_log_payment` FOREIGN KEY (`payment_id`) REFERENCES `payment` (`id`);

ALTER TABLE `person`
  ADD CONSTRAINT `fk_person_coach` FOREIGN KEY (`coach_id`) REFERENCES `user` (`id`);

ALTER TABLE `report`
  ADD CONSTRAINT `fk_report_action_plan` FOREIGN KEY (`action_plan_id`) REFERENCES `report_field` (`report_field_id`),
  ADD CONSTRAINT `fk_report_competences` FOREIGN KEY (`competences_id`) REFERENCES `report_field` (`report_field_id`),
  ADD CONSTRAINT `fk_report_competences_keywords` FOREIGN KEY (`competences_keywords_id`) REFERENCES `report_field` (`report_field_id`),
  ADD CONSTRAINT `fk_report_effectiveness` FOREIGN KEY (`effectiveness_id`) REFERENCES `report_field` (`report_field_id`),
  ADD CONSTRAINT `fk_report_effectiveness_keywords` FOREIGN KEY (`effectiveness_keywords_id`) REFERENCES `report_field` (`report_field_id`),
  ADD CONSTRAINT `fk_report_emergents` FOREIGN KEY (`emergents_id`) REFERENCES `report_field` (`report_field_id`),
  ADD CONSTRAINT `fk_report_emergents_keywords` FOREIGN KEY (`emergents_keywords_id`) REFERENCES `report_field` (`report_field_id`),
  ADD CONSTRAINT `fk_report_introduction` FOREIGN KEY (`introduction_id`) REFERENCES `report_field` (`report_field_id`),
  ADD CONSTRAINT `fk_report_introduction_keywords` FOREIGN KEY (`introduction_keywords_id`) REFERENCES `report_field` (`report_field_id`),
  ADD CONSTRAINT `fk_report_performance` FOREIGN KEY (`performance_id`) REFERENCES `report_field` (`report_field_id`),
  ADD CONSTRAINT `fk_report_performance_keywords` FOREIGN KEY (`performance_keywords_id`) REFERENCES `report_field` (`report_field_id`),
  ADD CONSTRAINT `fk_report_relations` FOREIGN KEY (`relations_id`) REFERENCES `report_field` (`report_field_id`),
  ADD CONSTRAINT `fk_report_relations_keywords` FOREIGN KEY (`relations_keywords_id`) REFERENCES `report_field` (`report_field_id`),
  ADD CONSTRAINT `fk_report_team` FOREIGN KEY (`team_id`) REFERENCES `team` (`id`);

ALTER TABLE `stock`
  ADD CONSTRAINT `fk_stock_coach` FOREIGN KEY (`coach_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `fk_stock_consumer` FOREIGN KEY (`consumer_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `fk_stock_creator` FOREIGN KEY (`creator_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `fk_stock_payment` FOREIGN KEY (`payment_id`) REFERENCES `payment` (`id`),
  ADD CONSTRAINT `fk_stock_product` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`),
  ADD CONSTRAINT `fk_stock_team` FOREIGN KEY (`team_id`) REFERENCES `team` (`id`);

ALTER TABLE `team`
  ADD CONSTRAINT `fk_team_team_type` FOREIGN KEY (`team_type_id`) REFERENCES `team_type` (`id`);

ALTER TABLE `team_type`
  ADD CONSTRAINT `fk_team_type_product` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`);

ALTER TABLE `team_type_dimension`
  ADD CONSTRAINT `fk_team_type_dimension_team_type` FOREIGN KEY (`team_type_id`) REFERENCES `team_type` (`id`);

ALTER TABLE `transaction`
  ADD CONSTRAINT `fk_transaction_creator` FOREIGN KEY (`creator_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `fk_transaction_payment` FOREIGN KEY (`payment_id`) REFERENCES `payment` (`id`);

ALTER TABLE `transaction_log`
  ADD CONSTRAINT `fk_transaction_log_creator` FOREIGN KEY (`creator_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `fk_transaction_log_transaction` FOREIGN KEY (`transaction_id`) REFERENCES `transaction` (`id`);

ALTER TABLE `user_session`
  ADD CONSTRAINT `fk_user_session_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

ALTER TABLE `wheel`
  ADD CONSTRAINT `fk_wheel_observed` FOREIGN KEY (`observed_id`) REFERENCES `person` (`id`),
  ADD CONSTRAINT `fk_wheel_observer` FOREIGN KEY (`observer_id`) REFERENCES `person` (`id`),
  ADD CONSTRAINT `fk_wheel_team` FOREIGN KEY (`team_id`) REFERENCES `team` (`id`);

ALTER TABLE `wheel_answer`
  ADD CONSTRAINT `fk_wheel_answer_question` FOREIGN KEY (`question_id`) REFERENCES `question` (`id`),
  ADD CONSTRAINT `fk_wheel_answer_wheel` FOREIGN KEY (`wheel_id`) REFERENCES `wheel` (`id`) ON DELETE CASCADE;

ALTER TABLE `wheel_question`
  ADD CONSTRAINT `fk_wheel_question_question` FOREIGN KEY (`question_id`) REFERENCES `question` (`id`),
  ADD CONSTRAINT `fk_wheel_question_team_type` FOREIGN KEY (`team_type_id`) REFERENCES `team_type` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
