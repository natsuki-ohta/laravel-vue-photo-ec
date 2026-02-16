# urls.py
from django.urls import path
from .views import ProductMapListView

urlpatterns = [
    path('api/map/products/', ProductMapListView.as_view()),
]