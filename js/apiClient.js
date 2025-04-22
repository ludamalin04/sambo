const API_BASE = 'https://dieslog.space/api';

export const apiClient = {
  getAll: async (table) => {
    const res = await fetch(`${API_BASE}/${table}`);
    return handleResponse(res);
  },

  getOne: async (table, id) => {
    const res = await fetch(`${API_BASE}/${table}/${id}`);
    return handleResponse(res);
  },

  create: async (table, data) => {
    const res = await fetch(`${API_BASE}/${table}`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(data)
    });
    return handleResponse(res);
  },

  update: async (table, id, data) => {
    const res = await fetch(`${API_BASE}/${table}/${id}`, {
      method: 'PUT',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(data)
    });
    return handleResponse(res);
  },

  remove: async (table, id) => {
    const res = await fetch(`${API_BASE}/${table}/${id}`, {
      method: 'DELETE'
    });
    return handleResponse(res);
  },

  custom: async (path) => {
    const res = await fetch(`${API_BASE}/${path}`);
    return handleResponse(res);
  }
};

const handleResponse = async (res) => {
  const data = await res.json();
  if (!res.ok) {
    throw new Error(data?.error || 'Unknown API error');
  }
  return data;
};
