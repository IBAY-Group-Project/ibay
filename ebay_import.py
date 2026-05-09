import re
import requests
import mysql.connector

# --- eBay credentials ---
CLIENT_ID     = 'MohmedAb-iBay-PRD-b6c21b7a2-4295f1cc'
CLIENT_SECRET = 'PRD-6c21b7a2b1a5-7260-4d3d-b907-5010'

# --- Database ---
DB = {
    'host':     'sci-project.lboro.ac.uk',
    'user':     'group01',
    'password': 'Juvwusorn7rhFvEEdMaX',
    'database': 'group01'
}

IMPORT_USER_ID = 21

# eBay category IDs mapped to your site's categories
CATEGORIES = {
    'Technology':  '293',    # Consumer Electronics
    'Clothing':    '11450',  # Clothing, Shoes & Accessories
    'Collectables':'220',    # Toys & Hobbies / Collectables
    'Gardening':   '159912', # Garden & Patio
    'Home':        '11700',  # Home & Garden
    'Sports':      '382',    # Sporting Goods
    'Books':       '267',    # Books
}


def get_token():
    r = requests.post(
        'https://api.ebay.com/identity/v1/oauth2/token',
        auth=(CLIENT_ID, CLIENT_SECRET),
        data={
            'grant_type': 'client_credentials',
            'scope': 'https://api.ebay.com/oauth/api_scope'
        }
    )
    return r.json()['access_token']


def browse_category(token, category_id, limit=50):
    r = requests.get(
        'https://api.ebay.com/buy/browse/v1/item_summary/search',
        headers={
            'Authorization': f'Bearer {token}',
            'X-EBAY-C-MARKETPLACE-ID': 'EBAY_GB'
        },
        params={
            'category_ids': category_id,
            'limit':        limit,
            'sort':         'bestMatch',
            'filter':       'price:[5..300],priceCurrency:GBP,buyingOptions:{FIXED_PRICE}',
        }
    )
    return r.json().get('itemSummaries', [])


def map_condition(ebay_cond):
    return {
        'NEW':             'New',
        'LIKE_NEW':        'Like New',
        'USED_EXCELLENT':  'Like New',
        'USED_VERY_GOOD':  'Used - Good',
        'USED_GOOD':       'Used - Good',
        'USED_ACCEPTABLE': 'Used - Acceptable',
    }.get(ebay_cond, 'Used - Good')


def map_postage(shipping):
    if not shipping:
        return 'Free postage'
    cost = float(shipping[0].get('shippingCost', {}).get('value', 0))
    if cost == 0: return 'Free postage'
    if cost <= 2: return '£1.99'
    if cost <= 3: return '£2.99'
    return '£4.99'


def hd(url):
    return re.sub(r's-l\d+', 's-l1600', url)


def import_category(category, limit=10):
    category_id = CATEGORIES.get(category)
    if not category_id:
        print(f"Unknown category: {category}")
        return

    token = get_token()
    listings = browse_category(token, category_id, limit)

    conn   = mysql.connector.connect(**DB)
    cursor = conn.cursor()
    count  = 0

    for item in listings:
        images = []
        if item.get('image', {}).get('imageUrl'):
            images.append(hd(item['image']['imageUrl']))
        for extra in item.get('additionalImages', []):
            images.append(hd(extra['imageUrl']))
            break

        if len(images) < 2:
            continue
        images = images[:2]

        title       = item.get('title', '')[:40]
        price       = int(float(item.get('price', {}).get('value', 0)))
        condition   = map_condition(item.get('condition', ''))
        postage     = map_postage(item.get('shippingOptions', []))
        description = item.get('shortDescription') or title

        cursor.execute("SELECT COUNT(*) FROM iBayItems WHERE title = %s AND userId = %s", (title, IMPORT_USER_ID))
        if cursor.fetchone()[0] > 0:
            continue

        cursor.execute(
            "INSERT INTO iBayItems "
            "(userId, title, category, description, price, postage, `condition`, sold) "
            "VALUES (%s, %s, %s, %s, %s, %s, %s, 0)",
            (IMPORT_USER_ID, title, category, description, price, postage, condition)
        )
        item_id = cursor.lastrowid

        for url in images:
            cursor.execute(
                "INSERT INTO iBayImages (image, mimeType, imageSize, itemId) "
                "VALUES (%s, %s, %s, %s)",
                (url, 'image/jpeg', 0, item_id)
            )

        count += 1
        print(f"  [{category}] {title}  £{price}")

    conn.commit()
    cursor.close()
    conn.close()
    print(f"  → {count} imported\n")


if __name__ == '__main__':
    import_category('Technology',  limit=10)
    import_category('Clothing',    limit=10)
    import_category('Collectables',limit=10)
    import_category('Home',        limit=10)
    import_category('Sports',      limit=10)
    import_category('Books',       limit=10)
    import_category('Gardening',   limit=10)
