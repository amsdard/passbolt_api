install:
	docker-compose exec passbolt /bin/bash -c \
	'su -m -c "/usr/share/php/passbolt/bin/cake passbolt install" -s /bin/sh www-data'

healthcheck:
	docker-compose exec passbolt /bin/bash -c \
	'su -m -c "/usr/share/php/passbolt/bin/cake passbolt healthcheck" -s /bin/sh www-data'

register:
	docker-compose exec passbolt /bin/bash -c \
      'su -m -c "/usr/share/php/passbolt/bin/cake passbolt register_user -u admin@amsterdamstandard.local \
       -f name  -l lastname  -r admin" -s /bin/sh www-data'

send-test-email:
	docker-compose exec passbolt /bin/bash -c \
	'su -m -c "/usr/share/php/passbolt/bin/cake passbolt send_test_email --recipient=youremail@domain.com" -s /bin/sh www-data'