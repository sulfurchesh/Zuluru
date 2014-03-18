<?php
class UsersController extends AppController {

	var $name = 'Users';
	var $uses = array('Person', 'Group');

	function publicActions() {
		return array('login', 'logout', 'create_account', 'reset_password');
	}

	function freeActions() {
		return array('logout');
	}

	function isAuthorized() {
		// Anyone that's logged in can perform these operations
		if (in_array ($this->params['action'], array(
				'id',
		)))
		{
			return true;
		}

		// People can perform these operations on their own account
		if (in_array ($this->params['action'], array(
				'change_password',
		)))
		{
			// If a player id is specified, check if it's the logged-in user
			// If no player id is specified, it's always the logged-in user
			$person = $this->_arg('user');
			if (!$person || $person == $this->Auth->user('zuluru_person_id')) {
				return true;
			}
		}

		return false;
	}
 
	/**
	 *  Code inside this function will execute only when autoRedirect was set to false
	 * (i.e. in a beforeFilter).
	 */
	function login() {
		$user = $this->Auth->user();
		$auth =& $this->Auth->authenticate;
		if ($user) {
			if (!empty($this->data[$auth->alias]['remember_me'])) {
				$this->Cookie->write('Auth.User', $this->data[$auth->alias], true, '+1 year');
			}

			$this->redirect($this->Auth->redirect());
		}

		$this->set('failed', !empty($this->data));

		// Set some variables the login page needs to properly render the form
		$this->set ('model', $auth->alias);
		$this->set ('user_field', $auth->userField);
		$this->set ('pwd_field', $auth->pwdField);
	}

	function logout() {
		if ($this->Cookie->read('Auth.User')) {
			$this->Cookie->delete('Auth.User');
		}
		$this->Session->delete('Zuluru');
		$this->redirect($this->Auth->logout());
	}

	function create_account() {
		if (!Configure::read('feature.manage_accounts')) {
			$this->Session->setFlash (__('This system uses ' . Configure::read('feature.manage_name') . ' to manage user accounts. Account creation through Zuluru is disabled.', true), 'default', array('class' => 'info'));
			$this->redirect('/');
		}

		if (!$this->is_admin && !$this->is_manager && $this->Auth->user('zuluru_person_id')) {
			$this->Session->setFlash(__('You are already logged in!', true), 'default', array('class' => 'info'));
			$this->redirect('/');
		}

		$this->_loadAddressOptions();
		$this->_loadGroupOptions();
		$this->_loadAffiliateOptions();

		if (!empty($this->data)) {
			$this->Person->create();
			$this->data['Person']['complete'] = true;
			$this->data['Person']['group_id'] = 1;	// TODO: Assumed this is the Player group
			if (Configure::read('feature.auto_approve')) {
				$this->data['Person']['status'] = 'active';
			}

			// Handle affiliations
			if (Configure::read('feature.affiliates')) {
				if (Configure::read('feature.multiple_affiliates')) {
					if (empty($this->data['Affiliate']['Affiliate'][0])) {
						$this->Person->Affiliate->validationErrors['Affiliate'] = __('You must select at least one affiliate that you are interested in.', true);
					}
				} else {
					if (empty($this->data['Affiliate']['Affiliate'][0]) || count($this->data['Affiliate']['Affiliate']) > 1) {
						$this->Person->Affiliate->validationErrors['Affiliate'] = __('You must select an affiliate that you are interested in.', true);
					}
				}
			} else {
				$this->data['Affiliate']['Affiliate'] = array(1);
			}

			if ($this->Person->saveAll($this->data, array('validate' => 'only')) && $this->Person->Affiliate->validates()) {
				if ($this->Person->saveAll($this->data)) {
					if (Configure::read('feature.auto_approve')) {
						$this->Session->setFlash('<h2>' . __('THANK YOU', true) . '</h2><p>' . sprintf(__('for creating an account with %s.', true), Configure::read('organization.name')) . '</p>', 'default', array('class' => 'success'));
					} else {
						$this->Session->setFlash(__('Your account has been created. It must be approved by an administrator before you will have full access to the site. However, you can log in and start exploring right away.', true), 'default', array('class' => 'success'));
					}

					// There may be callbacks to handle
					// TODO: How to handle this in conjunction with third-party auth systems?
					$this->data['Person']['id'] = $this->Person->id;
					$components = Configure::read('callbacks.user');
					foreach ($components as $name => $config) {
						$component = $this->_getComponent('User', $name, $this, false, $config);
						$component->onAdd($this->data);
					}

					if (!$this->is_logged_in) {
						// Automatically log the user in
						$this->data[$this->Auth->authenticate->alias]['password'] = $this->data[$this->Auth->authenticate->alias]['passwd'];
						$this->Auth->login($this->Auth->hashPasswords($this->data));
					}

					$this->redirect('/');
				} else {
					$this->Session->setFlash(sprintf(__('The %s could not be saved. Please correct the errors below and try again.', true), __('account', true)), 'default', array('class' => 'warning'));
				}
			} else {
				$this->Session->setFlash(sprintf(__('The %s could not be saved. Please correct the errors below and try again.', true), __('account', true)), 'default', array('class' => 'warning'));
			}
		}

		$this->set(array(
				'user_model' => $this->Auth->authenticate->name,
				'id_field' => $this->Auth->authenticate->primaryKey,
				'user_field' => $this->Auth->authenticate->userField,
				'email_field' => $this->Auth->authenticate->emailField,
		));
	}

	function import() {
		$columns = $this->Person->_schema;
		foreach (array('id', 'user_id', 'group_id', 'user_name', 'email', 'complete', 'twitter_token', 'twitter_secret') as $no_import) {
			unset($columns[$no_import]);
		}
		foreach (array_keys($columns) as $column) {
			if (!Configure::read("profile.$column")) {
				unset($columns[$column]);
			}
		}
		$columns['password'] = true;
		$this->set('columns', array_keys($columns));

		// Add other columns that we'll accept but are mentioned separately in the view
		// Columns set to "true" are for the user record; anything else goes in the person record
		$columns['id'] = array(true);
		$columns['user_name'] = true;
		$columns['email'] = true;

		if (!empty($this->data)) {
			// Handle affiliations
			if (Configure::read('feature.affiliates')) {
				if (Configure::read('feature.multiple_affiliates')) {
					if (empty($this->data['Affiliate']['Affiliate'][0])) {
						$this->Affiliate->Affiliate->validationErrors['Affiliate'] = __('You must select at least one affiliate.', true);
					}
				} else {
					if (empty($this->data['Affiliate']['Affiliate'][0]) || count($this->data['Affiliate']['Affiliate']) > 1) {
						$this->Affiliate->Affiliate->validationErrors['Affiliate'] = __('You must select an affiliate.', true);
					}
				}
			}

			$continue = true;
			if (empty($this->data['Person']['on_error'])) {
				$this->Person->validationErrors['on_error'] = __('Select how to handle fields with errors in them.', true);
				$continue = false;
			}
			if (empty($this->data['Person']['status'])) {
				$this->Person->validationErrors['status'] = __('Select a status for imported accounts.', true);
				$continue = false;
			}
			if (!empty($this->data['file']['error'])) {
				$this->Session->setFlash(__('There was an error uploading the file.', true), 'default', array('class' => 'info'));
				$continue = false;
			} else if ($this->data['file']['type'] != 'text/x-csv') {
				$this->Session->setFlash(__('Only import from CSV files is currently supported.', true), 'default', array('class' => 'info'));
				$continue = false;
			}

			if ($continue) {
				$file = fopen($this->data['file']['tmp_name'], 'r');
				$header = fgetcsv($file);
				$skip = array();
				foreach ($header as $key => $column) {
					if (!array_key_exists($column, $columns)) {
						unset($header[$key]);
						$skip[] = $column;
					}
				}
				if (!in_array('email', $header)) {
					$this->Session->setFlash(__('No email column was found.', true), 'default', array('class' => 'info'));
				} else {
					$this->set(compact('header', 'skip'));
					$remap = array(
						'user_name' => $this->Auth->authenticate->userField,
						'email' => $this->Auth->authenticate->emailField,
						'password' => 'passwd', // beforeSave looks for this and hashes it
					);
					$unmap = array_flip($remap);

					$succeeded = $resolved = $failed = array();

					while (($row = fgetcsv($file)) !== false) {
						// Skip rows starting with a #
						if ($row[0][0] == '#') {
							continue;
						}

						$continue = true;
						$errors = array();
						$data = array(
							// TODO: Hardcoded group id
							'Person' => array('group_id' => 1),
							$this->Auth->authenticate->alias => array(),
							'Affiliate' => $this->data['Affiliate'],
						);
						foreach ($header as $key => $column) {
							if (array_key_exists($column, $remap)) {
								$mapped_column = $remap[$column];
							} else {
								$mapped_column = $column;
							}
							if ($columns[$column] === true) {
								$data[$this->Auth->authenticate->alias][$mapped_column] = $row[$key];
							} else {
								$data['Person'][$mapped_column] = $row[$key];
							}
						}
						if (!empty($data['Person']['id'])) {
							$matches = $this->Person->find('count', array(
									'contain' => array(),
									'conditions' => array('id' => $data['Person']['id']),
							));
							if ($matches) {
								$errors[] = "id ({$data['Person']['id']} already taken)";
								$continue = false;
							}
						}
						if (empty($data[$this->Auth->authenticate->alias][$this->Auth->authenticate->userField])) {
							$user_name = $data[$this->Auth->authenticate->alias][$this->Auth->authenticate->emailField];
							if ($this->data['Person']['trim_email_domain']) {
								$user_name = $base_name = substr($user_name, 0, strpos($user_name, '@'));
								$append = 2;
								while (true) {
									if (!in_array($user_name, $succeeded) && !in_array($user_name, $resolved)) {
										$matches = $this->Auth->authenticate->find('count', array(
												'contain' => array(),
												'conditions' => array($this->Auth->authenticate->userField => $user_name),
										));
										if (!$matches) {
											break;
										}
									}
									$user_name = "$base_name$append";
									++ $append;
								}
							}
							$data[$this->Auth->authenticate->alias][$this->Auth->authenticate->userField] = $user_name;
						}
						if (empty($data[$this->Auth->authenticate->alias]['passwd'])) {
							$data[$this->Auth->authenticate->alias]['passwd'] = $this->_password(10);
						}
						$names = array();
						if (!empty($data['Person']['first_name'])) {
							$names[] = $data['Person']['first_name'];
						}
						if (!empty($data['Person']['last_name'])) {
							$names[] = $data['Person']['last_name'];
						}
						$data['Person']['full_name'] = implode(' ', $names);
						$data['Person']['email_formatted'] = "{$data['Person']['full_name']} <{$data[$this->Auth->authenticate->alias][$this->Auth->authenticate->emailField]}>";
						if (!empty($this->Auth->authenticate->nameField) && empty($data[$this->Auth->authenticate->alias][$this->Auth->authenticate->nameField])) {
							$data[$this->Auth->authenticate->alias][$this->Auth->authenticate->nameField] = $data['Person']['full_name'];
						}
						if (empty($data['Person']['status'])) {
							$data['Person']['status'] = $this->data['Person']['status'];
						}

						$success = $this->Person->saveAll($data, array('validate' => 'only'));

						foreach (array_keys($this->Auth->authenticate->validationErrors) as $column) {
							if (array_key_exists($column, $unmap)) {
								$mapped_column = $unmap[$column];
							} else {
								$mapped_column = $column;
							}
							$errors[] = "$mapped_column ({$data[$this->Auth->authenticate->alias][$column]})";
							$continue = false;
						}

						if ($continue && !$this->data['Person']['trial_run']) {
							if ($success) {
								$success = $this->Person->saveAll($data);
							} else {
								$old_validate = $this->Person->validate;
								if ($this->data['Person']['on_error'] == 'blank') {
									foreach (array_keys($this->Person->validationErrors) as $column) {
										unset($data['Person'][$column]);
										unset($this->Person->validate[$column]);
										$errors[] = "$column ('{$data['Person'][$column]}' blanked)";
									}
								} else if ($this->data['Person']['on_error'] == 'ignore') {
									foreach (array_keys($this->Person->validationErrors) as $column) {
										unset($this->Person->validate[$column]);
										$errors[] = "$column ('{$data['Person'][$column]}' imported anyway)";
									}
								} else {
									$continue = false;
								}

								if ($continue) {
									$success = $this->Person->saveAll($data, array('validate' => 'first'));
								}
								$this->Person->validate = $old_validate;
							}
						}

						if ($continue && $success) {
							if (empty($errors)) {
								$succeeded[] = "{$data[$this->Auth->authenticate->alias][$this->Auth->authenticate->userField]} ({$data[$this->Auth->authenticate->alias][$this->Auth->authenticate->emailField]})";
							} else {
								$resolved[] = "{$data[$this->Auth->authenticate->alias][$this->Auth->authenticate->userField]} ({$data[$this->Auth->authenticate->alias][$this->Auth->authenticate->emailField]})" .
										': ' . implode(', ', $errors);
							}
							if (!$this->data['Person']['trial_run'] && $this->data['Person']['notify_new_users']) {
								$this->set (array(
										'user' => $data,
										'user_model' => $this->Auth->authenticate->alias,
										'user_field' => $this->Auth->authenticate->userField,
								));
								$this->_sendMail (array (
										'to' => $data,
										'subject' => 'New account',
										'template' => 'account_new',
										'sendAs' => 'both',
								));
							}
						} else {
							unset($this->Person->validationErrors[$this->Auth->authenticate->alias]);
							foreach (array_keys($this->Person->validationErrors) as $column) {
								$errors[] = "$column ({$data['Person'][$column]})";
								if ($this->data['Person']['on_error'] == 'skip') {
									$continue = false;
								}
							}

							if ($continue) {
								$resolved[] = "{$data[$this->Auth->authenticate->alias][$this->Auth->authenticate->userField]} ({$data[$this->Auth->authenticate->alias][$this->Auth->authenticate->emailField]})" .
										': ' . implode(', ', $errors);
							} else {
								$failed[] = "{$data[$this->Auth->authenticate->alias][$this->Auth->authenticate->userField]} ({$data[$this->Auth->authenticate->alias][$this->Auth->authenticate->emailField]})" .
										': ' . implode(', ', $errors);
							}
						}
					}
				}
			}
		} else {
			// Set default state for checkboxes, since Cake doesn't allow default
			// settings in the input call for them.
			$this->data = array('Person' => array(
					'trim_email_domain' => true,
					'trial_run' => true,
					'notify_new_users' => true,
			));
		}

		$affiliates = $this->_applicableAffiliates(true);
		$this->set(compact('affiliates', 'succeeded', 'resolved', 'failed'));
	}

	function change_password() {
		$id = $this->_arg('user');
		if (!$id) {
			$id = $this->UserCache->read('Person.user_id');
		}
		if (!$id) {
			$this->Session->setFlash(sprintf(__('Invalid %s', true), __('user', true)), 'default', array('class' => 'info'));
			$this->redirect('/');
		}

		// Read this before trying to save, so that things like the current password are
		// available for validation
		$user_model = $this->Auth->authenticate->name;
		$this->Auth->authenticate->contain('Person');
		$user = $this->Auth->authenticate->read(null, $id);

		if (!empty($this->data)) {
			$this->data[$user_model]['password'] = $user[$user_model]['password'];
			if ($this->Person->$user_model->save($this->data)) {
				$this->Session->setFlash(__('The password has been updated', true), 'default', array('class' => 'success'));
				$this->redirect('/');
			} else {
				$this->Session->setFlash(__('The password could not be updated. Please, try again.', true), 'default', array('class' => 'warning'));
			}
		} else {
			$this->data = $user;
		}
		$this->set(compact('user'));
		$this->set('is_me', ($this->UserCache->read('Person.user_id') == $id));
		$this->set(array(
				'user_model' => $this->Auth->authenticate->name,
				'id_field' => $this->Auth->authenticate->primaryKey,
		));
	}

	function reset_password($id = null, $code = null) {
		if ($this->Auth->user('zuluru_person_id') !== null) {
			$this->Session->setFlash (__('You are already logged in. Use the change password form instead.', true), 'default', array('class' => 'info'));
			$this->redirect(array('action' => 'change_password'));
		}

		$user_model = $this->Auth->authenticate->name;

		if ($code !== null) {
			// Look up the provided code
			$this->Person->contain($user_model);
			$matches = $this->Person->read (null, $id);
			if (!$matches || str_replace('/', '_', substr($matches[$user_model]['password'], -8)) != $code) {
				$this->Session->setFlash(__('The provided code is not valid!', true), 'default', array('class' => 'warning'));
			} else {
				if ($this->_email_new_password($matches['Person'])) {
					$this->Session->setFlash(__('Your new password has been emailed to you.', true), 'default', array('class' => 'success'));
					$this->redirect('/');
				} else {
					$this->Session->setFlash(__('There was an error emailing your new password to you, please try again. If you have continued problems, please contact the office.', true), 'default', array('class' => 'warning'));
				}
			}
		} else {
			if (!empty ($this->data)) {
				// Remove any empty fields
				foreach ($this->data[$user_model] as $field => $value) {
					if (empty ($value)) {
						unset ($this->data[$user_model][$field]);
					}
				}
				if (!empty ($this->data[$user_model])) {
					// Find the user and send the email
					if ($user_model == 'User') {
						$joins = array();
						$contain = array($user_model);
					} else {
						// To do a name or email lookup on a third-party database, we need that model
						// joined in explicitly, which doesn't always happen with a contain.
						$config = new DATABASE_CONFIG;
						$prefix = $this->Auth->authenticate->tablePrefix;
						if ($this->Auth->authenticate->useDbConfig != 'default') {
							$config_name = $this->Auth->authenticate->useDbConfig;
							$config = $config->$config_name;
							$prefix = "{$config['database']}.$prefix";
						}

						$joins = array(
							array(
								'table' => "$prefix{$this->Auth->authenticate->useTable}",
								'alias' => $user_model,
								'type' => 'LEFT',
								'foreignKey' => false,
								'conditions' => "$user_model.{$this->Auth->authenticate->primaryKey} = Person.user_id",
							),
						);
						$contain = array();
					}
					$matches = $this->Person->find ('all', array(
							'conditions' => $this->data[$user_model],
							'fields' => array('Person.*', "$user_model.*"),
							'joins' => $joins,
							'contain' => $contain,
					));
					switch (count($matches)) {
						case 0:
							$this->Session->setFlash(__('No matching accounts were found!', true), 'default', array('class' => 'info'));
							break;

						case 1:
							if ($this->_email_reset_code($matches[0]['Person'])) {
								$this->Session->setFlash(__('Your reset code has been emailed to you.', true), 'default', array('class' => 'success'));
								$this->redirect('/');
							} else {
								$this->Session->setFlash(__('There was an error emailing the reset code to you, please try again. If you have continued problems, please contact the office.', true), 'default', array('class' => 'warning'));
							}
							break;

						default:
							$this->Session->setFlash(__('Multiple matching accounts were found for this email address; you will need to specify the user name.', true), 'default', array('class' => 'info'));
							break;
					}
				}
			}
		}

		$this->set(array(
				'user_model' => $this->Auth->authenticate->name,
				'user_field' => $this->Auth->authenticate->userField,
				'email_field' => $this->Auth->authenticate->emailField,
		));
	}

	function _email_reset_code($user) {
		$this->set ($user);
		$this->set ('code', str_replace('/', '_', substr($user['password'], -8)));
		return $this->_sendMail (array (
				'to' => $user['email_formatted'],
				'subject' => 'Password reset code',
				'template' => 'password_reset',
				'sendAs' => 'both',
		));
	}

	function _email_new_password($user) {
		$user_model = $this->Auth->authenticate->name;
		$this->Person->$user_model->id = $user['user_id'];
		$password = $this->_password(10);
		$hashed = $this->Auth->authenticate->hashPasswords (array(
				$this->Auth->authenticate->alias => array(
						$this->Auth->authenticate->pwdField => $password,
				)
		));
		if ($this->Person->$user_model->saveField($this->Auth->authenticate->pwdField, $hashed[$this->Auth->authenticate->alias][$this->Auth->authenticate->pwdField])) {
			$this->set ($user);
			$this->set (compact('password'));
			return $this->_sendMail (array (
					'to' => $user['email_formatted'],
					'subject' => 'New password',
					'template' => 'password_new',
					'sendAs' => 'both',
			));
		}
		return false;
	}

	function _password($length) {
		$characters = '23456789ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz';
		$string_length = strlen($characters) - 1;
		$string = '';

		for ($p = 0; $p < $length; $p++) {
			$string .= $characters[mt_rand(0, $string_length)];
		}
		return $string;
	}

	function id() {
		return $this->Auth->user('zuluru_person_id');
	}
}
?>
