---
layout: post
title: Prosty CQRS
---


W tym miejscu zakładam, że chcesz, ale nie używasz **CQRS - Command Query Responsibility Segregation**. Zastosowanie tego wzorca w projekcie nie zawsze jest łatwe i przyjemne. Powodów może być wiele od wyboru implementacji, braku doświadczenia, aż po problem z przekonaniem reszty zespołu do tego podejścia.

Jeżeli nie wiesz czym jest CQRS, polecam przeczytać najpierw  [https://zawarstwaabstrakcji.pl/20170130-cqrs-w-praktyce-wprowadzenie-php](https://zawarstwaabstrakcji.pl/20170130-cqrs-w-praktyce-wprowadzenie-php). W skrócie CQRS wprowadza  koncept, który może bardzo ułatwić Ci pisanie kodu, wprowadza podział na **Write Model** i **Read Model**. Write Model służy do zmiany stanu systemu/modułu/komponentu natomiast *Read Model* do odczytu stanu.

Teraz jak użyć tego podejścia bez możliwości używania Command, CommandHandler, CommandBus, QueryBus ... i całej infrastruktury wokół. W bardziej klasycznym podejściu zamykamy swoją funkcjonalność w jakimś komponencie, może to być serwis czy moduł. Powiedzmy, że standardowo używamy ORM do zapisu i raz na jakiś czas tworzymy jakiś *native query* do optymalizacji odczytu z bazy.

# Command i CommandHandler

{% highlight php %}
{% include 2020-04-06/2020-04-06-command.php %}
{% endhighlight %}

Trzymając się wzoru wyżej, OrderService będzie pełnił funkcję fasady/API naszego modułu. Opisałem go jako *Command Bus* ponieważ będzie on agregował i wywoływał w sobie inne mniejsze serwisy, które będą odpowiednikami CommandHandlera. Argumenty metod można zamknąć w prosty DTO, co w przyszłości może pomóc na przejście w prawdziwy CQRS.


# Query i ReadModel 

{% highlight php %}
{% include 2020-04-06/2020-04-06-query.php %}
{% endhighlight %}

Możesz traktować OrderService jako fasadę, która metodą query udostępni ReadModel. Sugeruje Read Model zamknąć w drugim repozytorium, które zwraca tylko i wyłącznie proste DTO jako część API. Pozwoli to w obrębie modułu rozróżnić wewnętrzny Read Model, który znajdzie się on w OrderRepository i nie będzie częscią API. Taki podział na wewnętrzny i zewnętrzny ReadModel na pewno zaowocuje w przyszłości przy refaktoryzacji.

Oczywiście, jeżeli OrderReadRepository jest zbyt prymitywny, nic nie stoi na przeszkodzie zastosowania np. QueryService i odpowiedniej rozbudowie.

Co do samego podejścia w organizacji kodu proponuję, aby eksponować tylko OrderService, OrderReadRepository i DTO, które służą jako input i output. Można to zrobić np. poprzez umieszczanie tych klas wyżej w strukturze katalogów, przez co klasy beda się rzucać w oczy.

Podsumowując to podejście czerpie dużo dobrego z CQRS. Dla osób, które mają problem ze wdrożeniem tego wzorca może to być dobry początek to dalszych zmian. Kod piszemy wspólnie z zespołem więc, zamiast rewolucji lepiej wprowadzać ewolucję. Wstępne używanie serwisów zamiast handlerów i repozytorium ograniczonych do custom query może o wiele szybciej przejść przy code review niż wprowadzenie od razu 5-10 klas tylko po to, aby wdrożyć ideologię CQRS.