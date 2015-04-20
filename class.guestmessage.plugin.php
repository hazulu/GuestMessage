<?php defined('APPLICATION') or die;

$PluginInfo['GuestMessage'] = array(
    'Name' => 'Guest Message',
    'Description' => 'Adds a fixed message for all guests browsing your site.',
    'Version' => '0.3',
    'RequiredTheme' => false,
    'SettingsPermission' => array('Garden.Settings.Manage', 'GuestMessage.Settings.Manage'),
    'SettingsUrl' => '/dashboard/settings/guestmessage',
    'RegisterPermissions' => array('GuestMessage.Settings.Manage'),
    'MobileFriendly' => true,
    'HasLocale' => false,
    'Author' => 'Gianni DaSilva',
    'AuthorUrl' => 'http://infinitum.co/'
);

class GuestMessagePlugin extends Gdn_Plugin {

    public function Base_Render_Before($Sender) {
        $Sender->AddCssFile('plugins/GuestMessage/style.css');
        if(C('GuestMessage.Close', TRUE)) { // This is to prevent a guest, who has a cookie from closing the message prior to the close button being disabled, from seeing the messsage.
            $Sender->AddJsFile('script.js', 'plugins/GuestMessage');
        }
    }

    public function base_getAppSettingsMenuItems_handler ($sender) {
        $menu = &$sender->EventArguments['SideMenu'];
    }

    public function settingsController_guestMessage_create ($sender) {
        
        $sender->permission(array(
            'Garden.Settings.Manage',
            'GuestMessage.Settings.Manage'
        ));

        $sender->addSideMenu('dashboard/settings/plugins');
        $sender->setData('Title', t('Guest Message Settings'));
        $configurationModule = new ConfigurationModule($sender);

        $configurationModule->initialize(array(
            'GuestMessage.Message' => array(
                'Control' => 'TextBox',
                'LabelCode' => 'Edit the message you want your guests to see here!'
            ),
            
            'GuestMessage.Close' => array(
                'Control' => 'CheckBox',
                'LabelCode' => 'Toggle Close Message Button',
                'Default' => false
            )
        ));
        $configurationModule->renderAll();
    }

    public function Setup() {
        // Set up the plugin's default values
        SaveToConfig('GuestMessage.Message', "You can edit this guest message in the plugin settings! You can change the style of this message in the plugin folder's style.css, to customize it to your needs. You may also use <b>html</b> in your message!");
        SaveToConfig('GuestMessage.Close', FALSE);
   }

    public function onDisable () {
        // Remove your settings after disabling the plugin
        removeFromConfig('GuestMessage');
    }

    public function Base_AfterBody_Handler($Sender){
        $Session = Gdn::Session();
        // If the viewer is logged in, leave the function
        if ($Session->UserID > 0)  return;
        $message = (C('GuestMessage.Message'));
        $close = (C('GuestMessage.Message'));
        echo '<div class="guestmessagepopup">';
        if(C('GuestMessage.Close', TRUE)) {
            echo '<a href="#" class="Close close-guestmessagepopup"><span>&#215;</span></a>'; 
        }
        echo '<p>'.$message.'</p>';
        echo '</div>';
    }
}
