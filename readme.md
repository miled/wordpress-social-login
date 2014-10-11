## WordPress Social Login

WordPress Social Login allow your website readers and customers to register on using their existing social accounts IDs, eliminating the need to fill out registration forms and remember usernames and passwords. 
http://sociallogin.github.io/wordpress-social-login/

### WSL-2.2.2 Steam branch

This branch is dedicated to the improvement of the provider Steam (http://steampowered.com).

#### ToDos

##### WSL admin

- [x] make wsl accepts steam api keys
- [ ] update the section (how to create an app) in the Networks tab

##### Steam adapter 

- [x] add support for the new web api
  - [ ] optimizations
  - [ ] decide whether to keep the legacy api or not (community data)

##### Legacy accounts

- [ ] Convert the old user identifiers in wslusersprofiles (http://steamcommunity.com/openid/id/*) to 64bit SteamIDs.
