# Token data

body type :

```typescript
{
    iss : number, // the identifiant (connexion ID) of your user. (mail or phone number)
    uuid : string, // user id. By default it string because you can also use a uuid in you system
    type : string, // user type. We supose that you classify users in your app. If not make sure to put a default value
    iat : datetime, // date of creation
    exp : datetime, // experiation date
    sub : string // name of the token or the reason. 
}
```

## token->sub

Okay, let focus here. To generate the refresh and access token we will use this sub to verify the type of token.

The app take `AUTH` as access token and `REFRESH` as refresh token.