<?php
class BP_tinychat_group_chat extends BP_Group_Extension {	
	public function  __construct() {
		global $bp;
		$this->name = 'Group Chat';
		$this->slug = 'group-chat';
		$this->create_step_position = 378;
		$this->nav_item_position = 396;
		if ( groups_get_groupmeta( $bp->groups->current_group->id, 'bp_tinychat_group_chat_enabled' ) == '1' ) {
			$this->enable_nav_item = true;
		} else {
			$this->enable_nav_item = false;}
			}
public function create_screen($group_id = null) {
		global $bp;
		if ( !bp_is_group_creation_step( $this->slug ) )
			return false;
		wp_nonce_field( 'groups_create_save_' . $this->slug ); ?>
<input type="checkbox" name="bp_tinychat_group_chat_enabled" id="bp_tinychat_group_chat_enabled" value="1"  
			<?php if ( groups_get_groupmeta( $bp->groups->current_group->id, 'bp_tinychat_group_chat_enabled' ) == '1' ) {echo 'checked=1';}?>		/>
        Enable Group Chat
		<hr>
		<?php }
public function create_screen_save($group_id = null) {
		global $bp;
		check_admin_referer( 'groups_create_save_' . $this->slug );	
		if ( $_POST['bp_tinychat_group_chat_enabled'] == 1 ) {
			groups_update_groupmeta( $bp->groups->current_group->id, 'bp_tinychat_group_chat_enabled', 1 );}}
public	function edit_screen($group_id = null) {
		global $bp;
		if ( !groups_is_user_admin( $bp->loggedin_user->id, $bp->groups->current_group->id ) ) {
			return false;}	
		if ( !bp_is_group_admin_screen( $this->slug ) )
			return false;
		wp_nonce_field( 'groups_edit_save_' . $this->slug );?>
<input type="checkbox" name="bp_tinychat_group_chat_enabled" id="bp_tinychat_group_chat_enabled" value="1"  
<?php if ( groups_get_groupmeta( $bp->groups->current_group->id, 'bp_tinychat_group_chat_enabled' ) == '1' ) {echo 'checked=1';}?>/>
        Enable Group Chat
		<hr>
		<input type="submit" name="save" value="Save" />
		<?php }
public	function edit_screen_save($group_id = null) {
		global $bp;
		if ( !isset( $_POST['save'] ) )
			return false;
		check_admin_referer( 'groups_edit_save_' . $this->slug );
		if ( $_POST['bp_tinychat_group_chat_enabled'] == 1 ) {
			groups_update_groupmeta( $bp->groups->current_group->id, 'bp_tinychat_group_chat_enabled', 1 );
		} else {
			groups_update_groupmeta( $bp->groups->current_group->id, 'bp_tinychat_group_chat_enabled', 0 );}
		bp_core_add_message( __( 'Settings saved successfully', 'buddypress' ) );
		bp_core_redirect( bp_get_group_permalink( $bp->groups->current_group ) . 'admin/' . $this->slug );}
public	function display($group_id = null) {
		global $bp;
		if ( groups_is_user_member( $bp->loggedin_user->id, $bp->groups->current_group->id ) || groups_is_user_mod( $bp->loggedin_user->id, $bp->groups->current_group->id ) || groups_is_user_admin( $bp->loggedin_user->id, $bp->groups->current_group->id ) || is_super_admin() ) {
		$name=apply_filters( 'bp_get_group_name', $bp->groups->current_group->name );
		if(preg_match('/^[a-z0-9]/', $name=strtolower($name), $name=strip_tags($name)))
{
		$name=preg_replace('/[^a-zA-Z0-9]/','',$name);
		$prohash = hash('sha256',filter_input(INPUT_SERVER, 'HTTP_CLIENT_IP'));
		}?>
			<div id="item-body">
<style>#chat{height:98%;width:100%;left:0px;right:0px;bottom:0px;position:fixed;z-index:9999}</style>

<?php
echo '<div id="chat">
<script type="text/javascript">
var tinychat = ({
		room: "'.$name.'", 
		prohash: "'.$prohash.'",
		nick: "'.$bp->loggedin_user->fullname.'",
		wmode:"transparent",
		chatSmileys:"true", 
		youtube:"all",
		urlsuper: "'.filter_input(INPUT_SERVER, 'HTTP_HOST'). filter_input(INPUT_SERVER, 'REQUEST_URI').'", 
		desktop:"true",
		langdefault:"en"});
		</script>
<script src="https://cdn.ruddernation.com/js/eslag.js"></script>
<div id="client"></div></div>';?>
            <?php
		} 
           else {
			echo '<div id="message" class="error"><p>Sorry group chat is only available to group members, Please join or request to join the group.</p></div>';
			}}} bp_register_group_extension( 'BP_tinychat_group_chat' ); ?>
