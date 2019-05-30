Feature: Group tool
  In order to use the group tool
  The teachers should be able to create groups

  Background:
    Given I am a platform administrator
    And I am on course "TEMP" homepage

  Scenario: Create a group directory
    Given I am on "/main/group/group_category.php?cidReq=TEMP&id_session=0&action=add_category"
    When I fill in the following:
      | title | Group category 1   |
    And I press "group_category_submit"
    Then I should see "Category created"

  Scenario: Create 3 groups
    Given I am on "/main/group/group_creation.php?cidReq=TEMP&id_session=0"
    When I fill in the following:
      | number_of_groups | 3 |
    And I press "submit"
    Then I should see "New groups creation"
    When I fill in the following:
      | group_0_places | 1 |
      | group_1_places | 1 |
      | group_2_places | 1 |
      | group_3_places | 1 |
    And I press "submit"
    Then I should see "group(s) has (have) been added"

  Scenario: Change Group 0003 settings to make announcements private
    Given I am on "/main/group/group.php?cidReq=TEMP&id_session=0"
    And I follow "Group 0003"
    Then I should see "Group 0003"
    Then I follow "Edit this group"
    Then I check the "announcements_state" radio button with "2" value
    Then I press "Save settings"
    Then I should see "Group settings modified"

  Scenario: Change Group 0004 settings to make it private
    Given I am on "/main/group/group.php?cidReq=TEMP&id_session=0"
    And I follow "Group 0004"
    Then I should see "Group 0004"
    Then I follow "Edit this group"
    Then I check the "announcements_state" radio button with "2" value
    Then I press "Save settings"
    Then I should see "Group settings modified"

  Scenario: Create document folder in group
    Given I am on "/main/group/group.php?cidReq=TEMP&id_session=0"
    And I follow "Group 0001"
    Then I should see "Group 0001"
    And I follow "Documents"
    Then I should see "There are no documents to be displayed"
    Then I follow "Create folder"
    Then I should see "Create folder"
    Then I fill in the following:
      | dirname | My folder in group |
    And I press "create_dir_form_submit"
    Then I should see "Folder created"

  Scenario: Create document inside folder in group
    Given I am on "/main/group/group.php?cidReq=TEMP&id_session=0"
    And I follow "Group 0001"
    Then I should see "Group 0001"
    And I follow "Documents"
    Then I should see "My folder in group"
    Then I follow "My folder in group"
    Then I follow "Create a rich media page / activity"
    Then I should see "Create a rich media page"
    Then I fill in the following:
      | title | html test |
    And I fill in ckeditor field "content" with "My first HTML!!"
    Then I press "create_document_submit"
    Then I should see "Item added"

  Scenario: Upload a document inside folder in group
    Given I am on "/main/group/group.php?cidReq=TEMP&id_session=0"
    And I follow "Group 0001"
    Then I should see "Group 0001"
    And I follow "Documents"
    Then I should see "My folder in group"
    Then I follow "My folder in group"
    Then I follow "Upload documents"
    Then I follow "Upload (Simple)"
    Then I attach the file "web/css/base.css" to "file"
    Then wait for the page to be loaded
    Then I press "upload_submitDocument"
    Then wait for the page to be loaded
    Then I should see "File upload succeeded"

  Scenario: Delete 2 uploaded files
    Given I am on "/main/group/group.php?cidReq=TEMP&id_session=0"
    And I follow "Group 0001"
    Then I should see "Group 0001"
    And I follow "Documents"
    Then I should see "My folder in group"
    Then I follow "My folder in group"
    Then I follow "Delete"
    Then wait for the page to be loaded
    Then I should see "Are you sure to delete"
    Then I follow "delete_item"

  Scenario: Delete directory
    Given I am on "/main/group/group.php?cidReq=TEMP&id_session=0"
    And I follow "Group 0001"
    Then I should see "Group 0001"
    And I follow "Documents"
    Then I should see "My folder in group"
    Then I follow "Delete"
    Then wait for the page to be loaded
    Then I should see "Are you sure to delete"
    Then I follow "delete_item"

  Scenario: Add fapple to the Group 0001
    Given I am on "/main/group/group.php?cidReq=TEMP&id_session=0"
    And I follow "Group 0001"
    Then I should see "Group 0001"
    Then I follow "Edit this group"
    Then I should see "Group members"
    Then wait for the page to be loaded
    Then I follow "group_members_tab"
    Then I select "Fiona Apple Maggart (fapple)" from "group_members"
    Then I press "group_members_rightSelected"
    Then I press "Save settings"
    And wait for the page to be loaded
    Then I should see "Group settings modified"
    Then I follow "Group 0001"
    Then I should see "Fiona"

  Scenario: Add fapple to the Group 0003
    Given I am on "/main/group/group.php?cidReq=TEMP&id_session=0"
    And I follow "Group 0003"
    Then I should see "Group 0003"
    Then I follow "Edit this group"
    Then I should see "Group members"
    Then wait for the page to be loaded
    Then I follow "group_members_tab"
    Then I select "Fiona Apple Maggart (fapple)" from "group_members"
    Then I press "group_members_rightSelected"
    Then I press "Save settings"
    And wait for the page to be loaded
    Then I should see "Group settings modified"
    Then I follow "Group 0003"
    Then I should see "Fiona"

  Scenario: Add acostea to the Group 0002
    Given I am on "/main/group/group.php?cidReq=TEMP&id_session=0"
    And I follow "Group 0002"
    Then I should see "Group 0002"
    Then I follow "Edit this group"
    Then I should see "Group members"
    Then wait for the page to be loaded
    Then I follow "group_members_tab"
    Then I select "Andrea Costea (acostea)" from "group_members"
    Then I press "group_members_rightSelected"
    Then I press "Save settings"
    And wait for the page to be loaded
    Then I should see "Group settings modified"
    Then I follow "Group 0002"
    Then I should see "Andrea"

  Scenario: Create an announcement for everybody inside Group 0001
    Given I am on "/main/group/group.php?cidReq=TEMP&id_session=0"
    And I follow "Group 0001"
    Then I should see "Group 0001"
    And I follow "Announcements"
    Then I should see "Announcements"
    Then I follow "Add an announcement"
    Then I should see "Add an announcement"
    Then wait for the page to be loaded
    Then I fill in the following:
      | title | Announcement for all users inside Group 0001 |
    And I fill in ckeditor field "content" with "Announcement description in Group 0001"
    Then I follow "announcement_preview"
    And wait for the page to be loaded
    Then I should see "Announcement will be sent to"
    Then I press "submit"
    Then I should see "Announcement has been added"

  Scenario: Create an announcement for fapple inside Group 0001
    Given I am on "/main/group/group.php?cidReq=TEMP&id_session=0"
    And I follow "Group 0001"
    Then I should see "Group 0001"
    And I follow "Announcements"
    Then I should see "Announcements"
    Then I follow "Add an announcement"
    Then I should see "Add an announcement"
    Then wait for the page to be loaded
    Then I press "choose_recipients"
    Then I select "Fiona Apple" from "users"
    Then I press "users_rightSelected"
    Then I fill in the following:
      | title | Announcement for user fapple inside Group 0001 |
    And I fill in ckeditor field "content" with "Announcement description for user fapple inside Group 0001"
    Then I follow "announcement_preview"
    And wait for the page to be loaded
    Then I should see "Announcement will be sent to"
    Then I press "submit"
    Then I should see "Announcement has been added"

  Scenario: Create an announcement for everybody inside Group 0003 (private)
    Given I am on "/main/group/group.php?cidReq=TEMP&id_session=0"
    And I follow "Group 0003"
    Then I should see "Group 0003"
    And I follow "Announcements"
    Then I should see "Announcements"
    Then I follow "Add an announcement"
    Then I should see "Add an announcement"
    Then wait for the page to be loaded
    Then I fill in the following:
      | title | Announcement for all users inside Group 0003 |
    And I fill in ckeditor field "content" with "Announcement description in Group 0003"
    Then I follow "announcement_preview"
    And wait for the page to be loaded
    Then I should see "Announcement will be sent to"
    Then I press "submit"
    Then I should see "Announcement has been added"

  Scenario: Create an announcement for fapple inside Group 0003
    Given I am on "/main/group/group.php?cidReq=TEMP&id_session=0"
    And I follow "Group 0003"
    Then I should see "Group 0003"
    And I follow "Announcements"
    Then I should see "Announcements"
    Then I follow "Add an announcement"
    Then I should see "Add an announcement"
    Then wait for the page to be loaded
    Then I press "choose_recipients"
    Then I select "Fiona Apple" from "users"
    Then I press "users_rightSelected"
    Then I fill in the following:
      | title | Announcement for user fapple inside Group 0003 |
    And I fill in ckeditor field "content" with "Announcement description for user fapple inside Group 0003"
    Then I follow "announcement_preview"
    And wait for the page to be loaded
    Then I should see "Announcement will be sent to"
    Then I press "submit"
    Then I should see "Announcement has been added"

  Scenario: Check fapple access of announcements Group 0001 (fapple group)
    Given I am logged as "fapple"
    And I am on course "TEMP" homepage
    And I am on "/main/group/group.php?cidReq=TEMP&id_session=0"
    And I follow "Group 0001"
    Then I should see "Group 0001"
    Then I follow "Announcements"
    And wait for the page to be loaded
    Then I should see "Announcement for all users inside Group 0001"
    Then I should see "Announcement for user fapple inside Group 0001"
    Then I follow "Announcement for user fapple inside Group 0001 Group"
    Then I should see "Announcement description for user fapple inside Group 0001"
    Then I move backward one page
    Then wait for the page to be loaded
    Then I should see "Announcement for all users inside Group 0001"
    Then I follow "Announcement for all users inside Group 0001"
    Then I should see "Announcement description in Group 0001"

  Scenario: Check fapple access of announcements Group 0003 (fapple group but private)
    Given I am logged as "fapple"
    And I am on course "TEMP" homepage
    And I am on "/main/group/group.php?cidReq=TEMP&id_session=0"
    And I follow "Group 0003"
    Then I should see "Group 0003"
    Then I follow "Announcements"
    And wait for the page to be loaded
    Then I should see "Announcement for all users inside Group 0003"
    Then I should see "Announcement for user fapple inside Group 0003"
    Then I follow "Announcement for user fapple inside Group 0003 Group"
    Then I should see "Announcement description for user fapple inside Group 0003"
    Then I move backward one page
    Then wait for the page to be loaded
    Then I should see "Announcement for all users inside Group 0003"
    Then I follow "Announcement for all users inside Group 0003"
    Then I should see "Announcement description in Group 0003"

  Scenario: Check acostea access of announcements in Group 001
    Given I am logged as "acostea"
    And I am on course "TEMP" homepage
    Given I am on "/main/group/group.php?cidReq=TEMP&id_session=0"
    Then I should see "Group 0001"
    And I should see "Group 0002"
    And I should see "Group 0003"
    And I should see "Group 0004"
    And I follow "Group 0001"
    Then I should see "Group 0001"
    Then I follow "Announcements"
    And wait for the page to be loaded
    Then I should see "Announcement for all users inside Group 0001"
    Then I should not see "Announcement for user fapple inside Group 0001"
    Then I follow "Announcement for all users inside Group 0001"
    Then I should see "Announcement description in Group 0001"
    Given I am on "/main/group/group.php?cidReq=TEMP&id_session=0"
    And I should see "Group 0003"
    And I follow "Group 0003"
    Then I should see "Group 0003"
    Then I should not see "Announcements"
