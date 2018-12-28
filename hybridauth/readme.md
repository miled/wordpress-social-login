Currently migrating to Hybridauth 3.

Breaking changes from HA2 to HA3:

    - google: changed scope & deprecated some g+ apis
    - yahoo: opend id => oauth2
    - LinkedIn: oauth1 => oauth2, no contacts
    - live: renamed to windowslive
    - Steam: http => https (identifier included)


No adapter implemented yet on HA3:

    - goodreads
    - lastfm
    - yandex
    - mixi
    - 500px


Defunct providers/adapters :

    - aol: openid no longuer working
    - stackoverflow: openid to retire soon


New adapters added in HA3:

    - Blizzard US/EU/APAC
    - MicrosoftGraph
    - StackExchange
    - Discord
    - BitBucket
    - GitLab
    - Spotify
    - wechat
    - wechat china
    - EventBrite
    - Authentiq
    - SteemConnect
