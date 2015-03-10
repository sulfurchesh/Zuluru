<h2><?php __('Administrator Guide: League Management'); ?></h2>

<h3><?php __('Structure'); ?></h3>
<p><?php printf(__('Before starting to set up your leagues, it\'s important to understand the structure %s is expecting. Following this structure will mean that %s is optimized to work with you; trying to impose a different structure may result in some awkwardness as you try to do the unexpected.', true), ZULURU, ZULURU); ?></p>
<p><?php __('Each year has one or more seasons. Each season has one or more leagues. Each league has one or more divisions. Each division has a number of teams.'); ?></p>
<p><?php printf(__('Note that, although you may have an identical league in multiple seasons, or in the same season from year to year, in %s these are set up as different leagues. Similarly, although the same group of people might play under the same team name in more than one league, each instance of this will be a separate team. %s retains all past history and makes it available to those who look for it; players can scroll back through their past team history to relive glory days or remind themselves of old rivalries.', true), ZULURU, ZULURU); ?></p>

<h4><?php __('Seasons'); ?></h4>
<p><?php __('A season is a period of time during which all of the games in a league will take place.'); ?></p>
<p><?php __('In practice, seasons are rather roughly defined, and may have some small overlap with each other. For example, if you have leagues that run from March through May, May through August, and September through December, then March to May might be your spring season, May to August might be summer, and September to December might be fall.'); ?></p>

<h4><?php __('Leagues'); ?></h4>
<p><?php __('A league is a group of divisions, collected together for display and logistics purposes.'); ?></p>
<p><?php __('There is no absolute rule to determine which divisions comprise each league. One very general rule is that any group of divisions that share "substantial similarities" might be grouped in a league. Alternately, if there is a reasonable chance of a team being moved from one division to another, either during a season or between seasons (e.g. teams being promoted or demoted based on performance, or shuffled for geographic or scheduling purposes), then those divisions should be in the same league. Yet another view is that if it is reasonable for a given individual to be on a team in each of two divisions, then those divisions should <strong>not</strong> be in the same league.'); ?></p>
<p><?php __('There may be more than one way of grouping divisions into leagues, you should use whatever is most logical for your organization. For example, if you have divisions for Juniors, Adults and "Masters" on each of Monday, Tuesday and Wednesday, you might set this up as Monday, Tuesday and Wednesday leagues, each with a Juniors, Adults and Masters division, or it might make more sense to have Juniors, Adults and Masters leagues, each with a Monday, Tuesday and Wednesday division.'); ?></p>

<h4><?php __('Divisions'); ?></h4>
<p><?php __('A division is a group of teams who will be scheduled to play against each other.'); ?></p>

<h3><?php __('Setup'); ?></h3>
<p><?php
printf(__('Once you have decided on your structure, start by creating the first league. There are only a handful of settings for leagues, which are shared by all divisions in that league. Once you have created the league, use the %s "add division" link from the league list to set up the first division.', true),
	$this->ZuluruHtml->icon('division_add_24.png')
); ?></p>
<p><?php
printf(__('Once you have one league and one division set up, you can often use the %s "clone league" or %s "clone division" links from the league list to very quickly set up a new league or division, with defaults all taken from the first. This way, you often need to change only one or two settings, or even just the name, rather than go through the entire setup again and again. When a new league or division is created this way, there is no link to the original; it is only used to find initial default settings. This means that if you make changes to the original, those changes are not automatically copied to the clone, but must be made manually (if they apply). However, this also means that you can use this clone process for almost all such setup, without fear that settings will inadvertently be altered at some later date by someone changing the original. By the time you have completed your first year of operation, you should have templates for almost every league and division you will ever offer, which you can continue to clone and be confident that the chance of something being configured incorrectly are extremely slim.', true),
	$this->ZuluruHtml->icon('league_clone_24.png'), $this->ZuluruHtml->icon('division_clone_24.png')
); ?></p>

<h3><?php __('Roster Rules'); ?></h3>
<p><?php __('If desired, you can add rules that must be met before a player can be added to the roster of a team in a particular division. Most commonly, this is used to enforce age or gender limits, confirm membership, or block people from joining more than one team, but there are other uses as well. See the help for the "roster rule" field for more information; setting up rules is currently a little bit complex, but there are examples provided that will cover most normal situations.'); ?></p>

<h3><?php __('Registration Interaction'); ?></h3>
<p><?php
printf(__('The optional %s has hooks which allow it to create teams directly in the correct divisions. If registrations are not enabled on your system, coaches and captains will manually create teams, which will be placed in the "Unassigned Teams" list (found under the Teams menu). It will be your responsibility to manually move each of these teams into the appropriate division.', true),
	$this->Html->link(__('registration system', true), array('action' => 'guide', 'administrator', 'registration'))
); ?></p>
