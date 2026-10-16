const eventList = document.querySelector("#event-list");

if (eventList) {
  const searchInput = document.querySelector("#event-search");
  const categorySelect = document.querySelector("#event-category");
  const sortSelect = document.querySelector("#event-sort");
  const statusMessage = document.querySelector("#events-status");
  const pagination = document.querySelector(".pagination");
  const previousButton = document.querySelector("#previous-page");
  const nextButton = document.querySelector("#next-page");
  const pageIndicator = document.querySelector("#page-indicator");
  const pageSize = 4;
  let events = [];
  let currentPage = 1;

  function makeTextElement(tagName, className, text) {
    const element = document.createElement(tagName);
    if (className) element.className = className;
    element.textContent = text;
    return element;
  }

  function createEventCard(event) {
    const card = document.createElement("article");
    card.className = "card";

    const header = document.createElement("header");
    header.className = "card-header";
    header.append(makeTextElement("span", "badge badge-primary", event.category));
    header.append(makeTextElement("h4", "", event.title));

    const body = document.createElement("div");
    body.className = "card-body";
    body.append(makeTextElement("p", "", event.description));
    body.append(makeTextElement("p", "event-meta", `${new Date(event.date).toLocaleString(undefined, {
      dateStyle: "medium",
      timeStyle: "short"
    })} · ${event.venue}`));

    const footer = document.createElement("footer");
    footer.className = "card-footer";
    const statusStyles = {
      "Registration open": "badge-success",
      "Limited seats": "badge-warning",
      "Coming soon": "badge-primary",
      "Registration closed": "badge-danger"
    };
    footer.append(makeTextElement("span", `badge ${statusStyles[event.status] || "badge-primary"}`, event.status));

    const registerLink = document.createElement("a");
    registerLink.className = "btn btn-sm btn-primary";
    registerLink.href = "register.html";
    registerLink.textContent = "Register";
    footer.append(registerLink);

    card.append(header, body, footer);
    return card;
  }

  function getVisibleEvents() {
    const query = searchInput.value.trim().toLocaleLowerCase();
    const category = categorySelect.value;
    const filteredEvents = events.filter((event) => {
      const searchableText = `${event.title} ${event.description} ${event.venue} ${event.category}`.toLocaleLowerCase();
      return searchableText.includes(query) && (category === "all" || event.category === category);
    });

    const sortDirection = sortSelect.value.endsWith("desc") ? -1 : 1;
    if (sortSelect.value.startsWith("title")) {
      filteredEvents.sort((first, second) => first.title.localeCompare(second.title) * sortDirection);
    } else {
      filteredEvents.sort((first, second) => (new Date(first.date) - new Date(second.date)) * sortDirection);
    }
    return filteredEvents;
  }

  function renderEvents() {
    const visibleEvents = getVisibleEvents();
    const pageCount = Math.max(1, Math.ceil(visibleEvents.length / pageSize));
    currentPage = Math.min(currentPage, pageCount);
    const firstResult = (currentPage - 1) * pageSize;
    const pageEvents = visibleEvents.slice(firstResult, firstResult + pageSize);

    eventList.replaceChildren(...pageEvents.map(createEventCard));
    eventList.setAttribute("aria-busy", "false");
    statusMessage.textContent = visibleEvents.length
      ? `Showing ${firstResult + 1}-${firstResult + pageEvents.length} of ${visibleEvents.length} events.`
      : "No events match your search and filter.";
    pagination.hidden = visibleEvents.length <= pageSize;
    previousButton.disabled = currentPage === 1;
    nextButton.disabled = currentPage === pageCount;
    pageIndicator.textContent = `Page ${currentPage} of ${pageCount}`;
  }

  function resetAndRender() {
    currentPage = 1;
    renderEvents();
  }

  searchInput.addEventListener("input", resetAndRender);
  categorySelect.addEventListener("change", resetAndRender);
  sortSelect.addEventListener("change", resetAndRender);
  previousButton.addEventListener("click", () => {
    if (currentPage > 1) {
      currentPage -= 1;
      renderEvents();
    }
  });
  nextButton.addEventListener("click", () => {
    const pageCount = Math.ceil(getVisibleEvents().length / pageSize);
    if (currentPage < pageCount) {
      currentPage += 1;
      renderEvents();
    }
  });

  fetch("data/events.json")
    .then((response) => {
      if (!response.ok) throw new Error(`Request failed with status ${response.status}`);
      return response.json();
    })
    .then((data) => {
      if (!Array.isArray(data)) throw new Error("The event data must be a JSON array.");
      events = data;
      [...new Set(events.map((event) => event.category))]
        .sort((first, second) => first.localeCompare(second))
        .forEach((category) => categorySelect.add(new Option(category, category)));
      renderEvents();
    })
    .catch(() => {
      eventList.setAttribute("aria-busy", "false");
      statusMessage.textContent = "Events could not be loaded. Open this page through a local web server and try again.";
    });
}
