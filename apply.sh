cd orders-api && sh k8s/docker/build.sh;
cd ..;
cd users-api && sh k8s/docker/build.sh;
cd ..;

kubectl delete -f orders-api/k8s/jobs/migrate.yml;
kubectl delete -f users-api/k8s/jobs/migrate.yml;

kubectl apply -R -f orders-api/k8s;
kubectl apply -R -f users-api/k8s;
sleep 3;
kubectl rollout restart deployment users-api users-horizon orders-api orders-horizon;
