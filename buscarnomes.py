import requests
from bs4 import BeautifulSoup

BASE_URL = "https://sistema.hostmarq.com.br/fotos/"

def get_image_list():
    response = requests.get(BASE_URL)
    if response.status_code == 200:
        soup = BeautifulSoup(response.text, 'html.parser')
        return [a['href'] for a in soup.find_all('a') if a['href'].endswith('.jpg')]
    return []

IMAGE_LIST = get_image_list()