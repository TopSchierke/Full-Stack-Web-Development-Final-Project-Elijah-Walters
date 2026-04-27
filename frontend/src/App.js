import { useEffect, useState } from "react";

const API = "http://localhost/shopping/backend";
//ChatGPT helped with some of the bootstrap
export default function App() {
  const [stores, setStores] = useState([]);
  const [newStore, setNewStore] = useState("");
  const [selectedStore, setSelectedStore] = useState("");

  const [items, setItems] = useState([]);
  const [itemName, setItemName] = useState("");
  const [itemQty, setItemQty] = useState(1);

  const loadStores = async () => {
    const res = await fetch(`${API}/stores.php`);
    setStores(await res.json());
  };

  const loadItems = async () => {
    const res = await fetch(`${API}/items.php`);
    setItems(await res.json());
  };

  useEffect(() => {
    loadStores();
    loadItems();
  }, []);


  const createStore = async () => {
    if (!newStore.trim()) return;

    await fetch(`${API}/stores.php`, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ name: newStore }),
    });

    setNewStore("");
    loadStores();
  };

  const deleteStore = async (id) => {
    await fetch(`${API}/stores.php?id=${id}`, {
      method: "DELETE",
    });

    setSelectedStore("");

    loadStores();
    loadItems();
  };

  const createItem = async () => {
    if (!itemName.trim() || !selectedStore) return;

    await fetch(`${API}/items.php?store_id=${selectedStore}`, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({
        name: itemName,
        quantity: itemQty,
      }),
    });

    setItemName("");
    setItemQty(1);
    loadItems();
  };

  const toggleChecked = async (item) => {
    const newChecked = Number(item.checked) === 1 ? 0 : 1;

    await fetch(`${API}/items.php?id=${item.id}`, {
      method: "PUT",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({
        name: item.name,
        quantity: item.quantity,
        checked: newChecked,
      }),
    });

    setItems((prev) =>
      prev.map((i) =>
        i.id === item.id ? { ...i, checked: newChecked } : i
      )
    );
  };

  const deleteItem = async (id) => {
    await fetch(`${API}/items.php?id=${id}`, {
      method: "DELETE",
    });

    setItems((prev) => prev.filter((i) => i.id !== id));
  };

  return (
    <div className="container py-4">

      <h2 className="mb-4">Shopping List</h2>

      <div className="card p-3 mb-3">
        <h5>Add Store</h5>
        <div className="d-flex gap-2">
          <input
            className="form-control"
            placeholder="Store name"
            value={newStore}
            onChange={(e) => setNewStore(e.target.value)}
          />
          <button className="btn btn-primary" onClick={createStore}>
            Add
          </button>
        </div>
      </div>

      <div className="card p-3 mb-3">
        <h5>Add Item</h5>

        <select
          className="form-select mb-2"
          value={selectedStore}
          onChange={(e) => setSelectedStore(e.target.value)}
        >
          <option value="">Select a Store</option>
          {stores.map((s) => (
            <option key={s.id} value={s.id}>
              {s.name}
            </option>
          ))}
        </select>

        <div className="d-flex gap-2">
          <input
            className="form-control"
            placeholder="Item name"
            value={itemName}
            onChange={(e) => setItemName(e.target.value)}
          />

          <input
            type="number"
            className="form-control"
            value={itemQty}
            onChange={(e) => setItemQty(e.target.value)}
            style={{ maxWidth: "100px" }}
          />

          <button className="btn btn-success" onClick={createItem}>
            Add
          </button>
        </div>

        <div className="mt-2 d-flex justify-content-end">
          {selectedStore && (
            <button
              className="btn btn-sm btn-danger"
              onClick={() => deleteStore(selectedStore)}
            >
              Delete Selected Store
            </button>
          )}
        </div>
      </div>

      <div className="card p-3">
        <h5>Shopping List</h5>

        {items.length === 0 && (
          <p className="text-muted">No items yet</p>
        )}

        {items.map((item) => (
          <div
            key={item.id}
            className="d-flex justify-content-between align-items-center border-bottom py-2"
          >
            <div>
              <input type="checkbox" className="form-check-input me-2" checked={Number(item.checked) === 1} onChange={() => toggleChecked(item)}/>

              <span
                style={{
                  textDecoration:
                    Number(item.checked) === 1 ? "line-through" : "none",
                }}
              >
                {item.name} ({item.quantity})
              </span>

              <small className="text-muted ms-2">
                : {item.store_name}
              </small>
            </div>

            <button
              className="btn btn-sm btn-outline-danger"
              onClick={() => deleteItem(item.id)}
            >
              Delete
            </button>
          </div>
        ))}
      </div>

    </div>
  );
}