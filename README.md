# Disable New Conversation Email Notification Per Mailbox - Freescout Module
BETA version ... not ready for production yet!!!

This is a module for Freescout that will allow users to disable the New Conversation email notification for any mailbox they have access too, on a per mailbox basis.

I am working on this new module that will allow users to disable the New Conversation email notification for any mailbox they have access too, on an individual mailbox basis.

The main reason for this module is that currently if you enable/check the email box for Notify Me When There Is A New Conversation https://yourfreescoutdomain.com/users/notifications/1 then you will get an email for every new conversation from every mailbox that you have access too.

But this is not always desired. As an example, we currently have 12 different mailboxes of which I need to have access to, but in 10 of them, I only need to respond if an email is specifically assigned to me. There are only 2 mailboxes that I am in charge of reading the new conversations and then assigning to others. So I only need to receive notificaiton of a new conversation for 2 of the 12 mailboxes.

WARNING - BETA version ... not ready for production yet!!!
- This module does work, however you need to create a new table in the DB first before you can use it.
- You also need to manually add the user_id and mailbox_id to the table as well before you can use it.

Below is a list of items that I do not know how to complete.

If anybody wants to contribute to the module, that would be great. I am even willing to completely hand it over to the Freescout team if they think they can polish it up and make it more user friendly.

TO DO - HELP LIST
- When module is activated, need to check if custom table in db exists, and if not then create it.
- Need to show a checkbox (one per mailbox) on the /users/notifications/userid page that is to the right of the "Notify Me When There Is A New Conversation", so that you can turn on/off the New Conversation Email for each mailbox individually (see attached mockup.jpg).
- Need to write the code that would actually update the custom table in the db when the user changes their preferences on the above mockup screen.

# Mockup of how it could look
![mockup](https://user-images.githubusercontent.com/19673842/222625051-06c4fcc2-d95a-4c89-877c-56ddc9bf3bbf.jpg)

# Need to create new table in freescout database

CREATE TABLE `subscriptions_disable_new_convo` (
  `id` int(10) NOT NULL,
  `user_id` int(10) NOT NULL,
  `mailbox_id` int(10) NOT NULL,
  `notes` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `subscriptions_disable_new_convo`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `subscriptions_disable_new_convo`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;
COMMIT;
