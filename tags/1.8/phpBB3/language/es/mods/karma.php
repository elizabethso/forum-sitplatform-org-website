<?php
/** 
*
* karma [Spanish]
*
* @package karmamod
* @version $Id: karma.php,v 68 2009/09/23 09:15:11 m157y Exp $
* @copyright (c) 2007, 2009 David Lawson, m157y, A_Jelly_Doughnut
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

/**
* DO NOT CHANGE
*/
if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

// DEVELOPERS PLEASE NOTE
//
// All language files should use UTF-8 as their encoding and the files must not contain a BOM.
//
// Placeholders can now contain order information, e.g. instead of
// 'Page %s of %s' you can (and should) write 'Page %1$s of %2$s', this allows
// translators to re-order the output of data while ensuring it remains correct
//
// You do not need this where single placeholders are used, e.g. 'Message %d' is fine
// equally where a string contains only two placeholders which are used to wrap text
// in a url you again do not need to specify an order e.g., 'Click %sHERE%s' is fine
//
// Some characters you may want to copy&paste:
// ’ » “ ” …
//

$lang = array_merge($lang, array(
	// [+] ACP variables
	'ACP_KARMA'							=> 'MOD Karma',
	'ACP_KARMA_CONFIG'					=> 'Configuración del MOD Karma',
	'ACP_KARMA_CONFIG_EXPLAIN'			=> 'Puedes cambiar la configuracion basica del MOD Karma.',
	'ACP_KARMA_HISTORY'					=> 'Historico del MOD Karma',
	'ACP_KARMA_HISTORY_EXPLAIN'			=> 'Esto es una lista de todas las modificaciones al karma en este foro.',
	'ACP_KARMA_STATS'					=> 'Estadisticas del MOD Karma',
	'ACP_KARMA_STATS_EXPLAIN'			=> 'Aqui puedes consultar estadisticas de modificaciones del karma y medias estadisticas de los usuarios de tu foro.',

	'ACP_KARMA_ANONYM_DECREASE'				=> 'Disminucion anonima del karma',
	'ACP_KARMA_ANONYM_DECREASE_EXPLAIN'		=> 'Si esta habilitado, solo los administradores podran visualizar las reducciones de karma.',
	'ACP_KARMA_ANONYM_INCREASE'				=> 'Aumento anonimo del karma',
	'ACP_KARMA_ANONYM_INCREASE_EXPLAIN'		=> 'Si esta habilitado, solo los administradores podran visualizar los aumentos de karma.',
	'ACP_KARMA_APPEND_COMMENTS'			=> 'comentarios',
	'ACP_KARMA_APPEND_POSTS'			=> 'mensajes',
	'ACP_KARMA_APPEND_TIME'				=> 'horas',
	'ACP_KARMA_APPEND_TIMES'			=> 'tiempos',
	'ACP_KARMA_BAN'								=> 'Automatically ban', // Need translate
	'ACP_KARMA_BAN_EXPLAIN'				=> 'Karma MOD can ban by karma minumum', // Need translate
	'ACP_KARMA_BAN_VALUE'					=> 'Ban karma value', // Need translate
	'ACP_KARMA_BAN_VALUE_EXPLAIN'	=> 'When user reach this karma, he will be banned', // Need translate
	'ACP_KARMA_BAN_REASON'				=> 'Ban reason', // Need translate
	'ACP_KARMA_BAN_REASON_EXPLAIN'	=> 'This text will be showed at ACP/MCP', // Need translate
	'ACP_KARMA_BAN_REASON_GIVE'		=> 'Reason shown to the banned', // Need translate
	'ACP_KARMA_BAN_REASON_GIVE_EXPLAIN'	=> 'This text will be showed for banned user', // Need translate
	'ACP_KARMA_BETA_VERSION'		=> 'Latest beta version', // Need translate
	'ACP_KARMA_COMMENTS'				=> 'Habilitar comentarios',
	'ACP_KARMA_COMMENTS_EXPLAIN'		=> 'Si esta opcion se encuentra habilitada, los usuarios pueden dejar comentarios para explicar por que han realizado una modificacion de karma.',
	'ACP_KARMA_COMMENTS_REQD'			=> 'Comentarios requeridos',
	'ACP_KARMA_COMMENTS_REQD_EXPLAIN'	=> 'Si esta opcion se encuentra habilitada, el usuario debe dejar un comentario cuando modifica karmas.',
	'ACP_KARMA_COMMENTS_PER_PAGE'		=> 'Comentarios por pagina',
	'ACP_KARMA_CONFIG_UPDATED'			=> 'La configuracion del MOD Karma se ha modificado con exito.',
	'ACP_KARMA_DRAFTS'					=> 'Habilitar borradores',
	'ACP_KARMA_DRAFTS_EXPLAIN'			=> 'Si se habilita, los comentarios podran utilizar el sistema de borradores, igual que los mensajes y los mensajes privados.',
	'ACP_KARMA_ENABLED'					=> 'Habilitar karma',
	'ACP_KARMA_ENABLED_EXPLAIN'			=> 'Si se deshabilita, todas las funcionalidades de karma se deshabilitaran y no se mostrara el karma en ninguna parte.',
	'ACP_KARMA_ENABLED_UCP'				=> 'Habilitar la desactivacion del karma',
	'ACP_KARMA_ENABLED_UCP_EXPLAIN'		=> 'Si se habilita, los usuarios pueden desactivar su karma',
	'ACP_KARMA_ICONS'					=> 'Habilitar iconos',
	'ACP_KARMA_ICONS_EXPLAIN'			=> 'Activar los iconos para los comentarios, igual que los iconos de los hilos del foro',
	'ACP_KARMA_MINIMUM'						=> 'Karma necesario',
	'ACP_KARMA_MINIMUM_EXPLAIN'			=> 'Despues de alcanzar esta puntuacion de karma, el usuario puede realizar modificaciones en el karma de otros usuarios',
	'ACP_KARMA_NOTIFY_EMAIL'			=> 'Habilitar notificaciones por correo electronico',
	'ACP_KARMA_NOTIFY_EMAIL_EXPLAIN'	=> 'Si se habilita, cada usuario puede deshabilitarlo de forma personal en su panel control de usuario.',
	'ACP_KARMA_NOTIFY_PM'				=> 'Habilitar notificaciones por mensaje privado',
	'ACP_KARMA_NOTIFY_PM_EXPLAIN'		=> 'Si se activa, cada usuario puede desactivarlo de forma personal en su panel de control de usuario.',
	'ACP_KARMA_NOTIFY_JABBER'			=> 'Habilitar notificaciones por jabber',
	'ACP_KARMA_NOTIFY_JABBER_EXPLAIN'	=> 'Si se activa, cada usuario puede desactivarlo de forma personal en su panel de control de usuario.',
	'ACP_KARMA_PER_DAY'					=> 'Limite de modificaciones al karma diarias',
	'ACP_KARMA_PER_DAY_EXPLAIN'			=> 'Numero de modificaciones al karma permitido, poner a cero para deshabilitar esta opcion.',
	'ACP_KARMA_POSTS'					=> 'Numero de mensajes necesarios',
	'ACP_KARMA_POSTS_EXPLAIN'			=> 'Despues de alcanzar este numero de mensajes, los usuarios ya pueden realizar modificaciones al karma.',
	'ACP_KARMA_POWER'					=> 'Habilitar el sistema de puntos de poder de karma',
	'ACP_KARMA_POWER_MAX'				=> 'Puntos maximos de poder de karma',
	'ACP_KARMA_POWER_SHOW'				=> 'Mostrar los puntos de poder de karma',
	'ACP_KARMA_POWER_SHOW_EXPLAIN'		=> 'Si se deshabilita, solo los administradores pueden ver los puntos de poder de karma.',
	'ACP_KARMA_REMOVE_INSTALL'			=> 'Por favor borra, mueve o renombra el directorio de instalacion del MOD Karma antes de comenzar a utilizar tu foro.',
	'ACP_KARMA_SETTINGS'				=> 'Configuracion del MOD Karma MOD',
	'ACP_KARMA_TIME'					=> 'Tiempo de Karma',
	'ACP_KARMA_TIME_EXPLAIN'			=> 'Cuanto tiempo deben esperar los usuarios antes de poder dar karma de nuevo',
	'ACP_KARMA_TOPLIST'					=> 'Habilitar la lista de los usuarios con mas karma',
	'ACP_KARMA_TOPLIST_EXPLAIN'			=> 'Si se habilita, se mostrara la lista de usuarios con mas karma en la pagina principal.',
	'ACP_KARMA_TOPLIST_USERS'			=> 'Numero de usuarios en la lista',
	'ACP_KARMA_TOPLIST_USERS_EXPLAIN'	=> 'Numero de usuarios que se mostraran en la lista de usuarios con mas karma, en la pagina principal.',
	'ACP_KARMA_UPDATER_BETA'			=> 'Check beta version updates', // Need translate
	'ACP_KARMA_VERSION_CHECK'			=> 'Comprobar la version del MOD Karma',
	'ACP_KARMA_VERSION_CHECK_MENU'					=> 'Comprobar si existen actualizaciones del MOD Karma',
	'ACP_KARMA_VERSION_CHECK_EXPLAIN'	=> 'Comprueba si la version del MOD Karma que estas ejecutando actualmente se encuentra actualizada.',
	'ACP_KARMA_VERSION_NOT_UP_TO_DATE_ACP'=> 'Tu version del MOD Karma no se encuentra actualizada.<br />A continuacion encontraras un vinculo al anuncio del lanzamiento de la ultima version.',
	'ACP_KARMA_VERSION_UP_TO_DATE_ACP'	=> 'Tu instalacion se encuentra actualizada, no existe ninguna nueva actualizacion disponible para tu version del MOD Karma. No necesitas actualizar tu instalacion.',
	'ACP_KARMA_VERSION_UPDATE_INSTRUCTIONS'			=> '<h1>Anuncio de lanzamiento</h1>

<p>Por favor lee <a href="%1$s" title="%1$s"><strong>el anuncio de lanzamiento de la ultima version</strong></a> antes de continuar con el proceso de actualizacion, podria incluir informacion util. Tambien contiene los vinculos completos de descarga, asi como el registro de cambios.</p>',
	'ACP_KARMA_VIEWPROFILE'				=> 'Habilitar visualizar comentarios',
	'ACP_KARMA_VIEWPROFILE_EXPLAIN'		=> 'Si se habilita, la seccion de comentarios sera visible en la página de perfil de cada usuario',
	'ACP_KARMA_ZEBRA'							=> 'Habilitar el karma para amigos y enemigos.',

	'KARMA_LOG_CONFIG'			=> '<strong>Configuracion del MOD Karma alterada</strong>',
	'KARMA_LOG_CLEAR'				=> '<strong>Se ha borrado el historial del MOD Karma</strong>',

	'IMG_ICON_KARMA_DECREASE'			=> 'Quitar Karma',
	'IMG_ICON_KARMA_INCREASE'			=> 'Dar Karma',

	'acl_f_karma_can'		=> array('lang' => 'Can karma users', 'cat' => 'misc'),	// Need translate
	'acl_f_karma_view'		=> array('lang' => 'Can view karma comments', 'cat' => 'misc'),	// Need translate
	'acl_u_karma_can'		=> array('lang' => 'Can karma users', 'cat' => 'misc'),	// Need translate
	'acl_u_karma_view'		=> array('lang' => 'Can view karma comments', 'cat' => 'misc'),	// Need translate
	// [-] ACP variables

	// [+] Install variables
	'INSTALL_KARMA_ADMIN_ONLY'					=> 'Lo siento, pero solo los usuarios con permisos administrativos pueden instalar o actualizar el MOD Karma.',
	'INSTALL_KARMA_CAT_KARMA'					=> 'Introduccion',
	'INSTALL_KARMA_CAT_INSTALL'					=> 'Instalacion',
	'INSTALL_KARMA_CAT_UPDATE'					=> 'Actualizacion',
	'INSTALL_KARMA_CONGRATS'					=> 'Enhorabuena!',
	'INSTALL_KARMA_CONGRATS_EXPLAIN'			=> '<p>Has instalado de forma correcta el MOD Karma %1$s. Si aprietas el boton que aparece a continuacion, accederas al Panel de Administracion. Tomate un tiempo para examinar las opciones disponibles. Recuerda que puedes buscar ayuda online dirigiendote al <a href="http://www.phpbb.com/community/viewtopic.php?f=70&t=559069">mensaje de soporte en phpBB.com</a>.</p><p><strong>Ahora por favor borra, mueve o renombra el directorio “install_karma” antes de acceder a tu foro.</strong></p>',
	'INSTALL_KARMA_CONGRATS_UPDATE'				=> 'Enhorabuena!',
	'INSTALL_KARMA_CONGRATS_UPDATE_EXPLAIN'		=> '<p>Has actualizado de forma correcta el MOD Karma a la version %1$s . Si aprietas el boton que aparece a continuacion, accederas al Panel de Administracion. Tomate un tiempo para examinar las opciones disponibles. Recuerda que puedes buscar ayuda online dirigiendote al <a href="http://www.phpbb.com/community/viewtopic.php?f=70&t=559069">mensaje de soporte en phpBB.com</a>.</p><p><strong>Ahora por favor borra, mueve o renombra el directorio “install_karma” antes de acceder a tu foro.</strong></p>',
	'INSTALL_KARMA_DB_USED'						=> ', actualmente utilizada',
	'INSTALL_KARMA_FILES_REQUIRED'				=> 'Ficheros y Carpetas',
	'INSTALL_KARMA_FILES_REQUIRED_EXPLAIN'		=> '<strong>Requerido</strong> - Para funcionar correctacmente, el MOD Karma necesita ser capaz de acceder a ciertos ficheros o carpetas. Si ves el mensaje "No Encontrado" deberás copiar ese fichero o carpeta desde la distribucion del Karma MOD que te has descargado.',
	'INSTALL_KARMA_INTRO'						=> 'Introduccion',
	'INSTALL_KARMA_INTRO_BODY'					=> 'Desde aqui puedes instalar o actualizar el MOD Karma en tu phpBB.</p><p>Para continuar, necesitaras una cuenta administrativa. No podras continuar sin ella.</p>

	<p>El MOD Karma para phpBB3 admite las siguientes bases de datos:</p>
	<ul>
		<li>MySQL 3.23 o superior (MySQLi soportado)</li>
		<li>PostgreSQL 7.3+</li>
		<li>SQLite 2.8.2+</li>
		<li>Firebird 2.0+</li>
		<li>MS SQL Server 2000 o superior (directamente o via ODBC)</li>
		<li>Oracle</li>
	</ul>
	
	<p>',
	'INSTALL_KARMA_INTRO_BODY_INSTALL'			=> 'Desde aqui puedes instalar el MOD Karma en tu phpBB.</p><p>Para continuar, necesitaras una cuenta administrativa. No podras continuar sin ella.</p>

	<p>El MOD Karma para phpBB3 admite las siguientes bases de datos:</p>
	<ul>
		<li>MySQL 3.23 o superior (MySQLi soportado)</li>
		<li>PostgreSQL 7.3+</li>
		<li>SQLite 2.8.2+</li>
		<li>Firebird 2.0+</li>
		<li>MS SQL Server 2000 o superior (directamente o via ODBC)</li>
		<li>Oracle</li>
	</ul>
	
	<p>',
	'INSTALL_KARMA_INTRO_BODY_UPDATE'			=> 'Desde aqui puedes actualizar el MOD Karma en tu phpBB.</p><p>Para continuar, necesitaras una cuenta administrativa. No podras continuar sin ella.',
	'INSTALL_KARMA_PHPBB_DRAFTS'				=> 'Habilitar Borradores',
	'INSTALL_KARMA_PHPBB_DRAFTS_EXPLAIN'		=> '<strong>Opcional</strong> - Esto es opcional, sin embargo los borradores de los comentarios no funcionaran sin el.',
	'INSTALL_KARMA_PHPBB_EMAIL'					=> 'El correo electronico se encuentra habilitado',
	'INSTALL_KARMA_PHPBB_EMAIL_EXPLAIN'			=> '<strong>Opcional</strong> - Esto es opcional, sin embargo las notificaciones a traves de correo electronico no funcionaran si se deshabilita.',
	'INSTALL_KARMA_PHPBB_JABBER'				=> 'Jabber se encuentra habilitado',
	'INSTALL_KARMA_PHPBB_JABBER_EXPLAIN'		=> '<strong>Opcional</strong> - Esto es opcional, sin embargo las notificaciones a traves de Jabber no funcionaran si se deshabilita..',
	'INSTALL_KARMA_PHPBB_PRIVMSGS'				=> 'Mensajes Privados habilitados',
	'INSTALL_KARMA_PHPBB_PRIVMSGS_EXPLAIN'		=> '<strong>Opcional</strong> - Esto es opcional, sin embargo las notificaciones a traves de mensajes privados no funcionaran si se deshabilita.',
	'INSTALL_KARMA_PHPBB_SETTINGS'				=> 'version de phpBB version y configuracion',
	'INSTALL_KARMA_PHPBB_SETTINGS_EXPLAIN'		=> '<strong>Requerido</strong> - Debes tener tu foro actualizado a la version 3.0.4 de phpBB para instalar el MOD Karma.',
	'INSTALL_KARMA_PHPBB_VERSION_REQD'			=> 'version phpBB >= 3.0.4',
	'INSTALL_KARMA_REQUIREMENTS_TITLE'			=> 'Compatibilidad de la instalacion',
	'INSTALL_KARMA_REQUIREMENTS_EXPLAIN'		=> 'Antes de llevar a cabo la instalacion completa, el MOD Karma va a realizar algunas pruebas en tu instalacion de phpBB3 para asegurar que es posible instalar y ejecutar el MOD Karma. Por favor asegurate de que lees detenidamente los resultados y no sigas adelante hasta que todas las comprobaciones se han pasado con exito. Si deseas utilizar cualquiera de las funcionalidades que dependen de los tests opciones, asegurate tambien de hacer correr esos tests.',
	'INSTALL_KARMA_REQUIREMENTS_UPDATE_TITLE'	=> 'Compatibilidad de la actualizacion',
	'INSTALL_KARMA_REQUIREMENTS_UPDATE_EXPLAIN'	=> 'Antes de llevar a cabo la actualizacion, el MOD Karma va a realizar algunas pruebas en tu instalacion de phpBB3 para asegurar que es posible actualizar el MOD Karma. Por favor asegurate de que lees detenidamente los resultados y no sigas adelante hasta que todas las comprobaciones se han pasado con exito. Si deseas utilizar cualquiera de las funcionalidades que dependen de los tests opciones, asegurate tambien de hacer correr esos tests.',
	'INSTALL_KARMA_SETTINGS'					=> 'version del MOD Karma',
	'INSTALL_KARMA_SETTINGS_EXPLAIN'			=> '<strong>Requerido</strong> - Debes tener tu foro actualizado a la version 3.0.4 de phpBB para actualizar el MOD Karma.',
	'INSTALL_KARMA_STAGE_ADMINISTRATOR'			=> 'Detalles de administracion',
	'INSTALL_KARMA_STAGE_CREATE_TABLE'			=> 'Crear tablas de la base de datos',
	'INSTALL_KARMA_STAGE_CREATE_TABLE_EXPLAIN'	=> 'Las tablas de la base de datos utilizadas por el MOD Karma han sido creadas y rellenadas con algunos datos iniciales. Pasa a la siguiente pantalla para terminar de instalar el MOD Karma.',
	'INSTALL_KARMA_STAGE_FINAL'					=> 'Paso Final',
	'INSTALL_KARMA_STAGE_INTRO'					=> 'Introduccion',
	'INSTALL_KARMA_STAGE_REQUIREMENTS'			=> 'Requisitos',
	'INSTALL_KARMA_STAGE_UPDATE'				=> 'Fase de actualizacion',
	'INSTALL_KARMA_STAGE_UPDATE_EXPLAIN'		=> 'Las tablas de la base de datos utilizadas por el MOD Karma han sido actualizadas y rellenadas con algunos datos que faltaban. Pasa a la siguiente pantalla para terminar de actualizar el MOD Karma.',
	'INSTALL_KARMA_SUB_INTRO'					=> 'Introduccion',
	'INSTALL_KARMA_SUB_LICENSE'					=> 'Licecia',
	'INSTALL_KARMA_SUB_SUPPORT'					=> 'Soporte',
	'INSTALL_KARMA_SUPPORT'						=> 'Soporte',
	'INSTALL_KARMA_SUPPORT_BODY'				=> 'Durante la fase beta, se prestara un soporte limitado en el <a href="http://www.phpbb.com/community/viewtopic.php?f=70&t=559069">Mensaje del MOD Karma MOD en el foro de phpBB 3.0.x "MODs en desarrollo"</a>. Proporcionaremos respuestas a preguntas generales de instalacion, problemas de configuracion y soporte de algunos problemas comunes relacionados generalmente con errores. Tambien permitimos discusiones sobre modificaciones y personalizaciones de codigo o estilos.</p>',
	'INSTALL_KARMA_UDPATE_START'				=> 'Comenzar la actualizacion',
	'INSTALL_KARMA_UDPATE_TEST'					=> 'Hacer correr los tests de nuevo',
	'INSTALL_KARMA_VERSION'						=> 'version del MOD Karma',
	'INSTALL_KARMA_VERSION_CURRENT'				=> 'version actualizada del MOD Karma',
	'INSTALL_KARMA_VERSION_NEED_UPDATE'			=> 'El MOD Karma MOD necesita actualizarse',

	'LOG_KARMA_INSTALLED'						=> '<strong>MOD Karma instalado %s</strong>',
	'LOG_KARMA_UPDATED'							=> '<strong>La version del MOD Karma se ha actualizado de la version %1$s a la version %2$s</strong>',
	// [-] Install variables

	// [+] Global variables
	'KARMA'							=> 'Karma',
	'KARMA_ALL_COMMENTS'	=> 'Todos los comentarios',
	'KARMA_ALREADY_KARMAED_POST'	=> 'Ya has dado karma en este mensaje.',
	'KARMA_CAN_NOT_KARMA_ZEBRA'		=> 'No puedes quitar o dar karma a tus amigos o enemigos.',
	'KARMA_CAN_NOT_MINIMUM'		=> 'Lo siento, pero no has alcanzado los puntos necesarios de karma para modificar el karma de otros usuarios.',
	'KARMA_CAN_NOT_POSTS'			=> 'Lo siento pero no has alcanzado el numero minimo de mensajes necesarios para dar o quitar karma a otros usuarios.',
	'KARMA_CAN_NOT_YET'				=> 'Lo siento pero todavia no puedes dar karma.',
	'KARMA_COMMENT'					=> 'Comentario',
	'KARMA_COMMENTS'				=> 'Comentarios',
	'KARMA_COMMENTS_DISABLED'		=> 'Los comentarios al Karma se han deshabilitado en este foro',
	'KARMA_COMMENTS_EXPLAIN'		=> 'Todos los comentarios al karma de este usuario se detallan a continuacion.',
	'KARMA_COMMENTS_SELF_ONLY'	=> 'Lo siento, pero no estas autorizado para ver el karma de este usuario',
	'KARMA_DECREASE'				=> 'Disminuir el karma del usuario',
	'KARMA_DECREASE_CONFIRM'		=> 'Estas seguro de quieres disminuir el karma del usuario seleccionado?',
	'KARMA_EXPLAIN'				=> 'Aqui puedes explicar, o escribir la razon por la cual estas aumentando o disminuyendo el karma de este usuario',
	'KARMA_ICON'					=> 'Icono de comentario',
	'KARMA_INCREASE'				=> 'Aumentar el karma del usuario',
	'KARMA_INCREASE_CONFIRM'		=> 'Estas seguro de quieres aumentar el karma del usuario seleccionado?',
	'KARMA_LIMITED_PER_DAY_TIME'	=> 'Solo puedes modificar el karma 1 vez al dia',
	'KARMA_LIMITED_PER_DAY_TIMES'	=> 'Solo puedes modificar el karma %1$s veces al dia',
	'KARMA_MOD_DISABLED'			=> 'Lo siento, pero el MOD Karma se encuentra deshabilitado en este momento.',
	'KARMA_NO_COMMENTS'				=> 'No existen comentarios al karma del usuario',
	'KARMA_NO_CURRENT_USER'			=> 'Los cambios al karma se han deshabilitado para este usuario',
	'KARMA_NO_ICON'					=> 'Sin icono de comentario',
	'KARMA_NO_KARMA_MODE'			=> 'No se ha especificado el modo del karma.',
	'KARMA_NO_SELF'					=> 'No puedes modificar tu propio karma.',
	'KARMA_NOTIFY_HIDDEN_SENDER' => 'Oculto',
	'KARMA_NOTIFY_INCREASE_SUBJECT'	=> 'Tu karma ha aumentado',
	'KARMA_NOTIFY_INCREASE_MESSAGE'	=> 'El usuario %1$s ha aumentado tu karma.',
	'KARMA_NOTIFY_INCREASE_MESSAGE_ANONYM'	=> 'Alguien ha aumentado tu karma.',
	'KARMA_NOTIFY_INCREASE_MESSAGE_POWERED'	=> 'El usuario %1$s ha aumentado tu karma con %2$d puntos de potencia.',
	'KARMA_NOTIFY_INCREASE_MESSAGE_POWERED_ANONYM'	=> 'Alguien ha aumentado tu karma con %2$d puntos de potencia.',
	'KARMA_NOTIFY_DECREASE_SUBJECT'	=> 'Tu karma ha disminuido',
	'KARMA_NOTIFY_DECREASE_MESSAGE'	=> 'El usuario %1$s ha disminuido tu karma',
	'KARMA_NOTIFY_DECREASE_MESSAGE_ANONYM'	=> 'Alguien ha disminuido tu karma',
	'KARMA_NOTIFY_DECREASE_MESSAGE_POWERED'	=> 'El usuario %1$s ha disminuido tu karma con %2$d puntos de potencia.',
	'KARMA_NOTIFY_DECREASE_MESSAGE_POWERED_ANONYM'	=> 'Alguien ha disminuido tu karma con %2$d puntos de potencia.',
	'KARMA_NOTIFY_MESSAGE_COMMENTS'	=> '%1$s ha dejado un comentario:',
	'KARMA_NOTIFY_BACKLINK_FORUM'	=> 'Se ha dejado un comentario para este foro: ',
	'KARMA_NOTIFY_BACKLINK_POST'	=> 'Se ha dejado un comentario para este mensaje: ',
	'KARMA_NOTIFY_BACKLINK_PROFILE'	=> 'Se ha dejado un comentario para tu perfil de usuario: ',
	'KARMA_NOTIFY_BACKLINK_TOPIC'	=> 'Se ha dejado un comentario para este hilo de discusion: ',
	'KARMA_POWER'					=> 'Puntos de potencia de Karma',
	'KARMA_RETURN_VIEWPROFILE'		=> '%sRegresar al ultimo perfil visitado%s',
	'KARMA_SORT_FORUM'					=> 'Foro con karma',
	'KARMA_SORT_POST'						=> 'Mensaje con karma',
	'KARMA_SORT_TIME'						=> 'Hora del comentario',
	'KARMA_SORT_TOPIC'					=> 'Hilo con Karma',
	'KARMA_SUCCESSFULLY_DECREASED'	=> 'Has disminuido el karma de este usuario.',
	'KARMA_SUCCESSFULLY_INCREASED'	=> 'Has aumentado el karma de este usuario.',
	'KARMA_TOPLIST'					=> 'Usuarios con mas karma',
	'KARMA_TOPLIST_EXPLAIN'			=> 'Lista de los usuarios con mas karma del foro',
	'KARMA_VIEW_COMMENTS'			=> 'Ver comentarios al karma',
	'KARMA_VIEW_USER_COMMENT'		=> '1 comentario',
	'KARMA_VIEW_USER_COMMENTS'		=> '%d comentarios',
	'KARMA_USER_COMMENTS'			=> 'Ver los comentarios al karma del usuario',
	'KARMA_USER_PROFILE'			=> 'Perfil del usuario',
	// [-] Global variables

	// [+] UCP variables
	'UCP_KARMA'							=> 'Editar configuracion del karma',
	'UCP_KARMA_COMMENTS_PER_PAGE'		=> 'Comentarios por pagina',
	'UCP_KARMA_COMMENTS_SELF'			=> 'Mostrar comentarios solo para mi',
	'UCP_KARMA_ENABLE'					=> 'Habilitar karma',
	'UCP_KARMA_ENABLE_EXPLAIN'			=> 'Si esta habilitado, los otros usuarios pueden modificar tu karma',
	'UCP_KARMA_NOTIFY_EMAIL'			=> 'Notificar por correo electronico',
	'UCP_KARMA_NOTIFY_EMAIL_EXPLAIN'	=> 'Si esta habilitado, recibiras notificaciones por correo electronico sobre las modificaciones a tu karma',
	'UCP_KARMA_NOTIFY_JABBER'			=> 'Notificar por jabber',
	'UCP_KARMA_NOTIFY_JABBER_EXPLAIN'	=> 'Si esta habilitado, recibiras notificaciones por jabber sobre las modificaciones a tu karma',
	'UCP_KARMA_NOTIFY_PM'				=> 'Notificar por mensaje privado',
	'UCP_KARMA_NOTIFY_PM_EXPLAIN'		=> 'Si esta habilitado, recibiras notificaciones por mensaje privado sobre las modificaciones a tu karma',
	'UCP_KARMA_TOPLIST'					=> 'Lista de usuarios con mas karma en la pagina indice',
	'UCP_KARMA_TOPLIST_EXPLAIN'			=> 'Si esta habilitado, podras ver la lista de usuarios con mas karma en la pagina indice',
	'UCP_KARMA_TOPLIST_USERS'			=> 'Usuarios en la lista de usuarios con mas karma',
	'UCP_KARMA_TOPLIST_USERS_APPEND'	=> 'usuarios',
	'UCP_KARMA_TOPLIST_USERS_EXPLAIN'	=> 'Numero de usuarios en la lista de usuarios con mas karma',
	'UCP_KARMA_UPDATED'					=> 'La configuracion de tu karma ha sido actualizada.',
	'UCP_KARMA_VIEW_COMMENTS_DAYS'		=> 'Mostrar comentarios de dias anteriores',
	'UCP_KARMA_VIEW_COMMENTS_DIR'			=> 'Mostrar la direccion del orden de los comentarios',
	'UCP_KARMA_VIEW_COMMENTS_KEY'			=> 'Visualizar los comentarios ordenados por',
	// [-] UCP variables
));

?>