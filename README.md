# MEC Change Order Status - Mangeto Extension

This extension expands core Magento functionality for order status management.

# Features
- Set up email notifications for order status changes (different email notifications can be set up for each status and store view)
- Change the order status for orders to ANY other order status (including «Complete» and «Closed» statuses).
- Change order statuses in bulk (mass action on the Orders page)
- Change order statuses in comment section on the Order page
- Multilingual support

# Installation

1. Copy all the files into your document root.
2. Clear the cache, logout from the admin panel and then login again.
3. You can now enable the extension via System -> Configuration -> MEC Extensions -> MEC Change Order Status

# Compatibility

- Magento >= 1.6 (tested for 1.9.3.10)

# Copyright

(c) 2015 devdl

# The original module seems abandoned 
PR: https://github.com/devdl/MEC_ChangeOrderStatus/pull/2 has not been merged for over a year.

I have clients who actively use this module, so I forked this and will add any fixes etc here.

Fixed:

1.0.0 : Remove old admin router definition
1.0.1 : Permisisons are wrong. If user is not admin role (all perms) they cannot change order status. The module exposes 'MEC Change Order Status' permisisons, so code was adjusted to use that value when set in role perms





