# views.py
from rest_framework.views import APIView
from rest_framework.response import Response
from .models import Product
from .serializers import ProductSerializer

class ProductMapListView(APIView):
    def get(self, request):
        products = Product.objects.filter(
            is_public=True,
            latitude__isnull=False,
            longitude__isnull=False,
        )

        serializer = ProductSerializer(products, many=True)
        return Response(serializer.data)