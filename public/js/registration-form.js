/**
 * Belgian Scouting Web Platform
 * Copyright (C) 2014  Julien Dupuis
 * 
 * This code is licensed under the GNU General Public License.
 * 
 * This is free software, and you are welcome to redistribute it
 * under under the terms of the GNU General Public License.
 * 
 * It is distributed without any warranty; without even the
 * implied warranty of merchantability or fitness for a particular
 * purpose. See the GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 **/

/**
 * This script is present on the registration form page
 */

$().ready(function() {
  // Show/hide leader specific fields when the is_leader switch is toggled
  $("#registration_form input[name='is_leader']").change(function(obj) {
    if ($("#registration_form input[name='is_leader']").prop("checked")) {
      $("#registration_form .leader_specific").slideDown();
    } else {
      $("#registration_form .leader_specific").slideUp();
    }
  });
  // Initially show the leader_specific fiels if needed
  if ($("#registration_form input[name='is_leader']").prop("checked")) {
    $("#registration_form .leader_specific").show();
  }
  
  // Prevent Enter key from validating the form
  document.onkeypress = function(evt) {
    var evt = (evt) ? evt : ((event) ? event : null);
    var node = (evt.target) ? evt.target : ((evt.srcElement) ? evt.srcElement : null);
    if ((evt.keyCode == 13) && ((node.type == "text" || node.type == "checkbox"))) {
      // Source: http://stackoverflow.com/questions/2455225/how-do-i-move-focus-to-next-input-with-jquery#2456761
      var inputs = $(evt.target).closest('form').find('input:visible,select:visible,checkbox:visible,textarea:visible');
      inputs.eq( inputs.index($(evt.target)) + 1 ).focus();
      return false;
    }
  };
  
});