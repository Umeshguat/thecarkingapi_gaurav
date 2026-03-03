# Instagram Feed API

## Setup

1. Copy `.env.example` to `.env` and fill in your Instagram API credentials:

```env
INSTAGRAM_ACCESS_TOKEN=your_access_token_here
INSTAGRAM_USER_ID=your_user_id_here
INSTAGRAM_REFRESH_TOKEN=your_refresh_token_here
INSTAGRAM_APP_ID=your_app_id_here
INSTAGRAM_APP_SECRET=your_app_secret_here
```

## API Endpoint

### Get Instagram Feed
**GET** `/api/v1/instagram/feed`

#### Parameters
- `limit` (optional): Number of posts to retrieve (default: 10, max: 25)

#### Response
```json
{
  "success": true,
  "data": [
    {
      "id": "post_id",
      "caption": "Post caption",
      "media_type": "IMAGE|VIDEO|CAROUSEL_ALBUM",
      "media_url": "https://example.com/media.jpg",
      "thumbnail_url": "https://example.com/thumb.jpg",
      "permalink": "https://instagram.com/p/post_id",
      "timestamp": "2024-01-01T12:00:00+0000",
      "username": "username",
      "formatted_date": "2024-01-01 12:00:00"
    }
  ],
  "message": "Instagram feed retrieved successfully"
}
```

## Instagram API Setup

1. Create a Facebook App at https://developers.facebook.com
2. Add Instagram Basic Display product
3. Configure OAuth redirect URIs
4. Get access token using Instagram's authentication flow
5. Add the credentials to your `.env` file

## Caching

The API response is cached for 1 hour to reduce API calls and improve performance.